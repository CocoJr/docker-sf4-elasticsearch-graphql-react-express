const CleanWebpackPlugin = require('clean-webpack-plugin');
const NodemonPlugin = require('nodemon-webpack-plugin');
const path = require('path');
const webpack = require('webpack');

const PROD = process.env.NODE_ENV === 'production';

const BASE_ASSETS_DIR = './';
const WEB_ASSETS_DIR = 'dist-server/';
const ASSETS_DIR = BASE_ASSETS_DIR + WEB_ASSETS_DIR;
const ABSOLUTE_ASSETS_DIR = path.resolve(__dirname, ASSETS_DIR);

let plugins = [
    new webpack.DefinePlugin({
        'process.env': {
            'NODE_ENV': JSON.stringify(process.env.NODE_ENV),
            '__SERVER__': 'true',
            '__CLIENT__': 'false',
        },
        NODE_ENV: JSON.stringify(process.env.NODE_ENV),
        __SERVER__: 'true',
        __CLIENT__: 'false',
        window: undefined,
    }),
    new CleanWebpackPlugin([WEB_ASSETS_DIR], {exclude:  ['.keep']}),
];

if (!PROD) {
    plugins.push(
        new NodemonPlugin({
            watch: ASSETS_DIR,
            script: ASSETS_DIR + 'server.js',
            verbose: true,
        }),
    );
}

module.exports = {
    entry: {
        'server': [
            'babel-polyfill',
            './src/server.js'
        ]
    },
    output: {
        filename: 'server.js',
        path: ABSOLUTE_ASSETS_DIR,
        publicPath: WEB_ASSETS_DIR,
        libraryTarget: "commonjs2"
    },
    target: 'node',
    externals: /^[a-z\-0-9]+$/,
    module: {
        loaders: [
            {
                test: /\.jsx?$/,
                exclude: /node_modules/,
                loader: 'babel-loader',
            }
        ]
    },
    plugins: plugins
};
