/*
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

import i18n from 'i18next';
import i18nConfig from './i18n';
import LanguageDetector from 'i18next-browser-languagedetector';

i18nConfig.react.wait = true;

i18n
    .use(LanguageDetector)
    .init(i18nConfig);

export default i18n;