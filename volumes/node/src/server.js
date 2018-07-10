import React from "react";
import { SheetsRegistry } from 'react-jss/lib/jss';
import JssProvider from 'react-jss/lib/JssProvider';
import {
    MuiThemeProvider,
    createGenerateClassName,
} from '@material-ui/core/es/styles/index';
import Theme from './app/Theme/theme';
const ReactDOMServer = require('react-dom/server');
const cookieParser = require('cookie-parser');
import express from "express";
import fetch from 'node-fetch';
import {ApolloProvider, getDataFromTree} from "react-apollo";
import {ApolloClient} from "apollo-client";
import {StaticRouter} from 'react-router-dom';
import {createUploadLink} from "apollo-upload-client/lib/main/index";
import {setContext} from "apollo-link-context";
import {InMemoryCache} from "apollo-cache-inmemory";
import App from "./app/Component/Core/app";
import Loadable from "react-loadable";
import { I18nextProvider } from 'react-i18next';
import i18n from './app/locales/server';
import {Helmet} from 'react-helmet';
import Moment from 'react-moment';

const server = express();
const middleware = require('i18next-express-middleware');

server.use(middleware.handle(i18n));
server.use(cookieParser());
server.use('/dist', express.static('dist'));

server.use((req, res) => {
    let language = req.url.replace(/^\/(fr|en)\/.*$/, '$1');
    if (!language.match(/(fr|en)/)) {
        language = i18n.options.fallbackLng[0];
        res.redirect('/'+language+req.url);
    }

    req.i18n.on('languageChanged', function(lng) {
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
    req.i18n.changeLanguage(language);

    const generateClassName = createGenerateClassName();

    let disableStylesGeneration = true;
    const getDisableStylesGeneration = () => disableStylesGeneration;

    const uploadLink = createUploadLink({
        fetch: fetch,
        uri: 'http://backend:8080',
        credentials: 'same-origin',
        headers: {
            cookie: req.header('Cookie'),
        },
    });

    const authLink = setContext((_, {headers}) => {
        return {
            headers: {
                ...headers,
                'X-Auth-Token': req.cookies['token'],
                'X-Locale': req.i18n.language,
            }
        }
    }).concat(uploadLink);

    const client = new ApolloClient({
        ssrMode: true,
        link: authLink,
        cache: new InMemoryCache(),
    });

    const sheetsRegistry = new SheetsRegistry();

    const app = <Server
        client={client}
        req={req}
        sheetsRegistry={sheetsRegistry}
        getDisableStylesGeneration={getDisableStylesGeneration}
        generateClassName={generateClassName}
    />;

    getDataFromTree(app).then(() => {
        disableStylesGeneration = false;
        const content = ReactDOMServer.renderToStaticMarkup(app);
        const initialState = client.extract();
        const css = sheetsRegistry.toString();
        const helmet = Helmet.renderStatic();

        const html = <Html content={content} state={initialState} css={css} helmet={helmet} />;

        res.status(200);
        res.send(`<!doctype html>\n${ReactDOMServer.renderToStaticMarkup(html)}`);
        res.end();
    }).catch((error) => {
        console.error(error);
        console.error(error.networkError);
        res.status(500);
        res.send(`<!doctype html><html>ERROR !<br>${error}</html>`);
        res.end();
    });
});

Loadable.preloadAll().then(() => {
    server.listen(8080, () => console.log('Server is ready'));
});

class Html extends React.Component {
    render() {
        const { content, state, css, helmet } = this.props;

        return (
            <html lang="fr">
                <head>
                    {helmet && helmet.title.toComponent()}
                    {helmet && helmet.meta.toComponent()}
                    {helmet && helmet.link.toComponent()}
                </head>
                <body>
                    <div id="root" dangerouslySetInnerHTML={{__html: content}}></div>
                    <script dangerouslySetInnerHTML={{
                        __html: `window.__APOLLO_STATE__=${JSON.stringify(state).replace(/</g, '\\u003c')};`,
                    }}></script>
                    <style id="jss-server-side" dangerouslySetInnerHTML={{__html: css}}></style>
                    <script src="/dist/vendor.js"></script>
                    <script src="/dist/bundle.js"></script>
                </body>
            </html>
        );
    }
}

class Server extends React.Component {
    render() {
        const { client, req, sheetsRegistry, getDisableStylesGeneration, generateClassName } = this.props;
        const context = {};

        return (
            <JssProvider registry={sheetsRegistry} generateClassName={generateClassName}>
                <MuiThemeProvider theme={Theme} sheetsManager={new Map()} disableStylesGeneration={getDisableStylesGeneration()}>
                    <ApolloProvider client={client}>
                        <I18nextProvider i18n={req.i18n} initialLanguage={req.i18n.language}>
                            <StaticRouter location={req.url} context={context}>
                                <App/>
                            </StaticRouter>
                        </I18nextProvider>
                    </ApolloProvider>
                </MuiThemeProvider>
            </JssProvider>
        );
    }
}
