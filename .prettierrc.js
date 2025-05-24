export default {
  tabWidth: 2,
  useTabs: false,
  printWidth: 70,
  singleQuote: true,
  htmlWhitespaceSensitivity: 'ignore',
  embeddedLanguageFormatting: 'auto',
  plugins: ['prettier-plugin-blade'],
  overrides: [
    {
      files: '**/*.blade.php',
      options: {
        parser: 'blade',
      },
    },
  ],
};
