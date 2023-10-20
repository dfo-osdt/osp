import { SpatieQuery } from '@/api/SpatieQuery';
import { Resource } from '../Resource';
import { http } from '@/api/http';

export interface Expertise {
    id: number;
    name_en: string;
    name_fr: string;
    tid: number;
    parent_tid: number;
    taxonomy_path_en?: string;
    taxonomy_path_fr?: string;
    ancestors?: ExpertiseResource[];
    children?: ExpertiseResource[];
}

export type ExpertiseResource = Resource<Expertise>;
export type ExpertiseResourceList = Resource<Expertise[]>;

type R = ExpertiseResource;
type RList = ExpertiseResourceList;

export class ExpertiseService {
    public static async list(query?: ExpertiseQuery) {
        let url = 'api/expertises';
        if (query) {
            url += `?${query.toQueryString()}`;
        }
        const response = await http.get<RList>(url);
        return response.data;
    }

    public static async get(id: number) {
        const response = await http.get<R>(`api/expertises/${id}`);
        return response.data;
    }
}

export class ExpertiseQuery extends SpatieQuery {
    public filterId(id: number) {
        return this.filter('id', id);
    }

    public filterTid(tid: number) {
        return this.filter('tid', tid);
    }

    public filterNameEn(name: string) {
        return this.filter('name_en', name);
    }

    public filterNameFr(name: string) {
        return this.filter('name_fr', name);
    }

    public search(search: string) {
        return this.filter('search', search);
    }

    public onlyMainExpertises() {
        return this.filter('main_expertises', 'true');
    }

    public includAncestors() {
        return this.include('ancestors');
    }

    public includeChildren() {
        return this.include('children');
    }
}
