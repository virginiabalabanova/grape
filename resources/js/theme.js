import resolveConfig from 'tailwindcss/resolveConfig';
import tailwindConfig from '../../tailwind.config.js';

const fullConfig = resolveConfig(tailwindConfig);

const fetchTheme = async () => {
  const response = await fetch('/api/theme');
  const themeCustomizations = await response.json();

  return {
    ...fullConfig.theme,
    extend: {
      ...fullConfig.theme.extend,
      ...themeCustomizations,
    },
  };
};

const theme = await fetchTheme();

export default theme;
