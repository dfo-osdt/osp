export function useDefaultOrganizationId() {
  return Number(import.meta.env.VITE_OSP_DEFAULT_ORG_ID) || 1
}
