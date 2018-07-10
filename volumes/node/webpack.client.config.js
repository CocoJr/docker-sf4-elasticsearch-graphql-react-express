/*
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

const CopyWebpackPlugin = require('copy-webpack-plugin')
const CleanWebpackPlugin = require('clean-webpack-plugin');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const UglifyJSPlugin = require('uglifyjs-webpack-plugin');
const webpack = require('webpack');
const CommonsChunkPlugin = webpack.optimize.CommonsChunkPlugin;

const PROD = process.env.NODE_ENV === 'production';

const BASE_ASSETS_DIR = './';
const WEB_ASSETS_DIR = 'dist/';
const ASSETS_DIR = BASE_ASSETS_DIR + WEB_ASSETS_DIR;

const path = require('path');
const ABSOLUTE_ASSETS_DIR = path.resolve(__dirname, ASSETS_DIR);

const filenameTemplate = !PROD ? '[name]' : '[chunkhash:12].[name]';

module.exports = {
    devtool: !PROD ? "cheap-module-eval-source-map" : false,
    entry: {
        'bundle': [
            'babel-polyfill',
            './src/app/index.js'
        ],
    },
    output: {
        filename: filenameTemplate + '.js',
        path: ABSOLUTE_ASSETS_DIR,
        publicPath: WEB_ASSETS_DIR,
    },
    plugins: [
        new webpack.DefinePlugin({
            'process.env': {
                'NODE_ENV': JSON.stringify(process.env.ENV === 'prod' ? 'production' : 'development'),
            },
            NODE_ENV: JSON.stringify(process.env.ENV === 'prod' ? 'production' : 'development'),
        }),
        new ExtractTextPlugin(filenameTemplate + '.js'),
        new UglifyJSPlugin({
            parallel: 4,
            cache: true,
            include: undefined,
            uglifyOptions: {
                mangle: false
            },
        }),
        new CommonsChunkPlugin({
            name: 'vendor',
            minChunks: (module) => module.context && module.context.includes('node_modules'),
        }),
        new CopyWebpackPlugin(
            [
                {
                    from: path.resolve(__dirname, './src/img'),
                    to: ABSOLUTE_ASSETS_DIR+'/img',
                }
            ],
            {
                debug: !PROD,
                copyUnmodified: !PROD,
            }
        ),
        new CleanWebpackPlugin([WEB_ASSETS_DIR], {exclude:  ['.keep']}),
    ],
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /(node_modules|bower_components)/,
                use: ["babel-loader"]
            },
            {
                test: /\.(png|jpg|jpeg|gif|eot|svg|woff2?|ttf)$/,
                use: {
                    loader: 'url-loader',
                    options: {
                        limit: 8192
                    }
                }
            },
        ]
    },
};
