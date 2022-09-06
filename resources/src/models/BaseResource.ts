export interface ModelPermissions {
    update?: boolean;
    delete?: boolean;
}

export interface BaseResource {
    can: Readonly<ModelPermissions>;
}