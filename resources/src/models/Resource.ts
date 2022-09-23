interface ModelPermissions {
    update?: boolean;
    delete?: boolean;
}

interface Meta {
    current_page: number;
}

interface Links {
    first: string;
    last: string;
    prev: string;
    next: string;
}

export interface Resource<T> {
    data: T;
    can?: Readonly<ModelPermissions>;
}

export interface ResourceList<T> {
    data: Resource<T>[];
    meta?: Readonly<Meta>;
    links?: Readonly<Links>;
}

export interface Media {
    file_name: string;
    size_bytes: number;
    created_at: string;
    collection_name: string;
    mime_type: string;
}
