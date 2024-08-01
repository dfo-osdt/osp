import typescriptEslint from "@typescript-eslint/eslint-plugin";
import prettier from "eslint-plugin-prettier";
import quasar from "eslint-plugin-quasar";
import globals from "globals";
import parser from "vue-eslint-parser";
import path from "node:path";
import { fileURLToPath } from "node:url";
import js from "@eslint/js";
import { FlatCompat } from "@eslint/eslintrc";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const compat = new FlatCompat({
    baseDirectory: __dirname,
    recommendedConfig: js.configs.recommended,
    allConfig: js.configs.all
});

export default [{
    ignores: ["**/auto-imports.d.ts"],
}, ...compat.extends(
    "plugin:@typescript-eslint/recommended",
    "plugin:vue/vue3-recommended",
    "prettier",
), {
    plugins: {
        "@typescript-eslint": typescriptEslint,
        prettier,
        quasar,
    },

    languageOptions: {
        globals: {
            ...globals.node,
        },

        parser: parser,
        ecmaVersion: 5,
        sourceType: "commonjs",

        parserOptions: {
            parser: "@typescript-eslint/parser",
        },
    },

    rules: {
        "prettier/prettier": ["warn", {
            singleQuote: true,
            jsxSingleQuote: true,
        }],
    },
}];