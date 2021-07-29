const path = require('path');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const OptimizeCSSAssetsPlugin = require("optimize-css-assets-webpack-plugin");
const TerserPlugin = require('terser-webpack-plugin');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
const VueLoaderPlugin = require('vue-loader/lib/plugin');
const svgToMiniDataURI = require('mini-svg-data-uri');

const config = require('./config.json');

module.exports = (env, argv) => {
	let isDev = argv.mode !== 'production';

	let plugins = [];

	plugins.push(new MiniCssExtractPlugin({
		filename: "../css/[name].css"
	}));

	plugins.push(new BrowserSyncPlugin({
		proxy: config.proxyURL
	}));

	plugins.push(new VueLoaderPlugin());

	const webpackConfig = {
		entry: config.entryPoints,
		output: {
			path: path.resolve(__dirname, 'assets/js'),
			filename: '[name].js'
		},
		devtool: isDev ? 'eval-source-map' : false,
		module: {
			rules: [
				{
					test: /\.(js|jsx)$/i,
					use: {
						loader: "babel-loader",
						options: {
							presets: [
								'@babel/preset-env',
								'@babel/preset-react'
							],
							plugins: [
								['@babel/plugin-proposal-class-properties', {'loose': true}],
								['@babel/plugin-proposal-private-methods', {'loose': true}],
								['@babel/plugin-proposal-object-rest-spread', {'loose': true}],
							]
						}
					}
				},
				{
					test: /\.vue$/i,
					use: [
						{loader: 'vue-loader'}
					]
				},
				{
					test: /\.(sass|scss|css)$/i,
					use: [
						{
							loader: isDev ? "style-loader" : MiniCssExtractPlugin.loader
						},
						{
							loader: "css-loader",
							options: {
								sourceMap: isDev,
								importLoaders: 1
							}
						},
						{
							loader: "postcss-loader",
							options: {
								sourceMap: isDev,
								postcssOptions: {
									plugins: [
										['postcss-preset-env'],
									],
								},
							},
						},
						{
							loader: "sass-loader",
							options: {
								sourceMap: isDev,
							},
						}
					]
				},
				{
					test: /\.(eot|ttf|woff|woff2)$/i,
					use: [
						{
							loader: 'file-loader',
							options: {
								outputPath: '../fonts',
							},
						},
					],
				},
				{
					test: /\.(png|je?pg|gif)$/i,
					use: [
						{
							loader: 'url-loader',
							options: {
								limit: 8192, // 8KB
								outputPath: '../images',
							},
						},
					],
				},
				{
					test: /\.svg$/i,
					use: [
						{
							loader: 'url-loader',
							options: {
								limit: 10240, // 10KB
								outputPath: '../images',
								generator: (content) => svgToMiniDataURI(content.toString()),
							},
						},
					],
				}
			]
		},
		optimization: {
			minimizer: [
				new TerserPlugin(),
				new OptimizeCSSAssetsPlugin()
			]
		},
		resolve: {
			alias: {
				'vue$': 'vue/dist/vue.esm.js',
				'@': path.resolve('./assets/src/'),
			},
			modules: [
				path.resolve('./node_modules'),
				path.resolve(path.join(__dirname, 'assets/src/')),
				path.resolve(path.join(__dirname, 'assets/src/shapla')),
			],
			extensions: ['*', '.js', '.vue', '.json']
		},
		plugins: plugins
	}

	webpackConfig.externals = {
		'jquery': 'jQuery',
		'react': 'React',
		'react-dom': 'ReactDOM'
	}

	return webpackConfig;
};
