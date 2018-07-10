/*
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

import i18n from 'i18next';
import i18nConfig from './i18n';

const middleware = require('i18next-express-middleware');

i18nConfig.react.wait = false;
i18n
    .use(middleware.LanguageDetector)
    .init(i18nConfig);

export default i18n;
