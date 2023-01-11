const { t } = useI18n();

type ValidationRule = 'email' | 'password';

export function useValidation(rule: ValidationRule) {
    const rules = {
        email: [
            (v: string) => !!v || t('common.validation.email-required'),
            (v: string) =>
                /.+@.+/.test(v) || t('common.validation.email-invalid'),
        ],
        password: [
            (v: string) => !!v || t('validation.required'),
            (v: string) => v.length >= 8 || t('validation.password'),
        ],
    };

    return computed(() => rules[rule]);
}
