const path = require('path');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");
const TerserPlugin = require('terser-webpack-plugin');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
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
					test: /\.tsx?$/,
					use: 'ts-loader',
					exclude: /node_modules/,
				},
				{
					test: /\.(js|jsx)$/i,
					use: {
						loader: "babel-loader",
						options: {
							presets: [
								'@babel/preset-env',
								'@babel/preset-react',
								"@babel/preset-typescript"
							],
							plugins: [
								['@babel/plugin-proposal-class-properties'],
								['@babel/plugin-proposal-private-methods'],
								['@babel/plugin-proposal-object-rest-spread'],
							]
						}
					}
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
					type: 'asset/resource',
					generator: {
						filename: '../fonts/[hash][ext]'
					}
				},
				{
					test: /\.(png|je?pg|gif)$/i,
					type: 'asset',
					generator: {
						filename: '../images/[hash][ext]'
					}
				},
				{
					test: /\.svg$/i,
					type: 'asset',
					generator: {
						filename: '../images/[hash][ext]',
						dataUrl: content => svgToMiniDataURI(content.toString())
					},
				}
			]
		},
		optimization: {
			minimizer: [
				new TerserPlugin(),
				new CssMinimizerPlugin()
			]
		},
		resolve: {
			alias: {
				'@': path.resolve('./resources/'),
			},
			modules: [
				path.resolve('./node_modules'),
				path.resolve(path.join(__dirname, 'resources/')),
			],
			fallback: {
				url: false
			},
			extensions: ['.js', '.jsx', '.ts', '.tsx']
		},
		plugins: plugins
	}

	webpackConfig.externals = {
		'jquery': 'jQuery',
		'react': 'React',
		'react-dom': 'ReactDOM',
		'@wordpress/blocks': 'wp.blocks',
		'@wordpress/block-editor': 'wp.blockEditor',
		'@wordpress/components': 'wp.components'
	}

	return webpackConfig;
};
