module.exports = {
    'env': {
        'browser': true,
        'jquery': true
    },
    'extends': 'eslint:recommended',
    'rules': {
        /*'no-undef': ['warn', {'typeof': true}],*/
        'no-undef': 0,
        'no-console': 0,
        'no-unused-vars': ['error',
            {'vars': 'all', 'args': 'after-used'}],
        'indent': [
            0,
            'tab'
        ],
        'linebreak-style': [
            'error',
            'unix'
        ],
        'quotes': [
            'error',
            'single'
        ],
        'semi': [
            'error',
            'always'
        ]
    }
};
