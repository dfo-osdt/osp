import { http } from "./http";

export const csrf = () => http.get("/sanctum/csrf-cookie");
