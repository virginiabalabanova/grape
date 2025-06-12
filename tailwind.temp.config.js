const themeConfig = require('./tailwind.theme.config.js');

module.exports = {
    ...themeConfig,
    theme: {
        ...themeConfig.theme,
        extend: {
            ...themeConfig.theme.extend,
            colors: {
                ...themeConfig.theme.extend.colors,
                ...require('./storage/app/colors.json'),
            },
        },
    },
};
