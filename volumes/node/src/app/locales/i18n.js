import fr from './fr';
import en from './en';

export default {
    // we init with resources
    resources: {
        fr: fr,
        en: en,
    },
    fallbackLng: ['fr', 'en'],
    debug: false,
    ns: ['global'],
    defaultNS: 'global',
    keySeparator: '.',
    interpolation: {
        escapeValue: false,
        formatSeparator: ','
    },
    react: {
        wait: false
    }
};