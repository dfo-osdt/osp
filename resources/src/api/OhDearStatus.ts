import { Locale } from '@/stores/LocaleStore';
import { http } from '@/api/http';

export interface OhDearStatusPage {
    title: string;
    timezone: string;
    pinnedUpdate: Update | null;
    sites: Sites;
    updatesPerDay: { [key: string]: Update[] };
    summarizedStatus: string;
}

export interface Update {
    id: number;
    title: string;
    text: string;
    pinned: boolean;
    severity: 'info' | 'warning' | 'high' | 'resolved' | 'scheduled';
    time: number;
}

export interface Sites {
    [key: string]: Site[];
}

export interface Site {
    label: string;
    url: string;
    status: string;
}

export class OhDearStatus {
    private readonly baseUrl: string;
    locale: Locale;
    OhDearStatusPage: OhDearStatusPage | null = null;

    constructor(baseUrl: string, locale: Locale) {
        this.baseUrl = baseUrl;
        this.locale = locale;
    }

    public async updateLocale(locale: Locale) {
        this.locale = locale;
        return this.updateStatus();
    }

    public async updateStatus() {
        const response = await http.get<OhDearStatusPage>(
            `${this.baseUrl}?locale=${this.locale}`
        );

        if (response.status !== 200) {
            throw new Error(`OhDearStatus: ${response.statusText}`);
        }
        this.OhDearStatusPage = response.data;
        return this.OhDearStatusPage;
    }

    public hasPinnedUpdate(): boolean {
        if (this.OhDearStatusPage === null) return false;
        if (this.OhDearStatusPage.pinnedUpdate === null) return false;
        return true;
    }

    public getPinnedUpdateDate(): Date | null {
        if (this.OhDearStatusPage === null) return null;
        if (this.OhDearStatusPage.pinnedUpdate?.time === undefined) return null;

        const secondsSinceEpoch = this.OhDearStatusPage.pinnedUpdate.time;
        const time = new Date(secondsSinceEpoch * 1000);
        return time;
    }

    public getPinnedUpdateId(): number | null {
        return this.OhDearStatusPage?.pinnedUpdate?.id ?? null;
    }
}
