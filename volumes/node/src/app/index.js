require('es6-promise').polyfill();
require('isomorphic-fetch');

import React from "react";
import ReactDOM from "react-dom";
import {ApolloProvider} from "react-apollo";
import {ApolloClient} from "apollo-client";
import {setContext} from "apollo-link-context";
import {onError} from "apollo-link-error";
import { createUploadLink } from 'apollo-upload-client'
import {InMemoryCache} from "apollo-cache-inmemory";
import {createBrowserHistory} from "history";
import { Router } from 'react-router-dom';
import Main from "./Component/Core/main";
import {getCookie} from "./Util/core";
import {MuiThemeProvider} from '@material-ui/core/es/styles/index';
import Theme from './Theme/theme';
import {I18nextProvider} from 'react-i18next';
import i18n from './locales/client';
import Moment from 'react-moment';

if (document.getElementById('root') !== null) {
    const history = createBrowserHistory();

    let language = history.location.pathname.replace(/^\/(fr|en)\/.*$/, '$1');
    if (!language.match(/(fr|en)/)) {
        language = i18n.options.fallbackLng[0];
    }

    i18n.on('languageChanged', function(lng) {
        Moment.globalLocale = lng;
        switch (lng) {
            case 'fr':
                Moment.globalFormat = 'DD/MM/YYYY HH:mm';
                break;
            case 'en':
                Moment.globalFormat = 'YYYY-MM-DD hh:mma';
                break;
        }
    });
    i18n.changeLanguage(language);

    // GraphQL
    const uploadLink = createUploadLink({
        uri: 'http://site.local:8080/',
        credentials: 'same-origin',
    });

    const link = onError(({response, operation, graphQLErrors, networkError}) => {
        if (graphQLErrors)
            graphQLErrors.map(({message, locations, path}) =>
                console.error(
                    `[GraphQL error]: Message: ${message}, Location: ${locations}, Path: ${path}`,
                ),
            );

        if (networkError) console.error(`[Network error]: ${networkError}`);

        if (response) {
            response.errors = null;
        }
    }).concat(uploadLink);

    const authLink = setContext((_, {headers}) => {
        return {
            headers: {
                ...headers,
                'X-Auth-Token': getCookie('token'),
                'X-Locale': i18n.language,
            }
        }
    }).concat(link);

    const client = new ApolloClient({
        link: authLink,
        cache: new InMemoryCache().restore(window.__APOLLO_STATE__),
    });

    ReactDOM.hydrate(
        <MuiThemeProvider theme={Theme}>
            <ApolloProvider client={client}>
                <I18nextProvider i18n={i18n}>
                    <Router history={history}>
                        <Main/>
                    </Router>
                </I18nextProvider>
            </ApolloProvider>
        </MuiThemeProvider>,
        document.getElementById('root')
    );

    //registerServiceWorker();
}
