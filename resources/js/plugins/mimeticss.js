import expand from 'emmet';

/**
 * String.prototype.replaceAll() polyfill
 * https://gomakethings.com/how-to-replace-a-section-of-a-string-with-another-one-with-vanilla-js/
 * @author Chris Ferdinandi
 * @license MIT
 */
 if (!String.prototype.replaceAll) {
	String.prototype.replaceAll = function(str, newStr){

		// If a regex pattern
		if (Object.prototype.toString.call(str).toLowerCase() === '[object regexp]') {
			return this.replace(str, newStr);
		}

		// If a string
		return this.replace(new RegExp(str, 'g'), newStr);

	};
}

export default function mimeticss(args = {}) {

    global.expand = expand;

    const config = {
        abbrSplitter: '+#0_+',
        breakpoints: {
            base: '',
            xl: 1400,
            lg: 1200,
            md: 992,
            sm: 768,
            xs: 576,
        },
        mobileFirst: true,
        colors: {
            t: 'transparent',
            w: 'white',
            b: 'black',
        },
        fonts: {
            0: 'icon',
            1: 'Arial',
            2: 'Comic Sans Ms',
        },
        css: {},
        output: {}
    };

    //deep assign arguments
    for (let x in config) {
        switch (true) {
            case (typeof (args[x]) === 'object'):
                Object.assign(config[x], args[x]);
                break;
            case (typeof (args[x]) !== 'undefined'):
                config[x] = args[x];
                break;
        }
    }

    //console.log(config);

    //process_text();
    setInterval(function () {
        const date_start = new Date().getTime();
        //console.log(date_start);
        init();

        const date_end = new Date().getTime();
        const date_diff = date_end - date_start;
        //console.log(`script processed in ${date_diff} ms`);
    }, 1000);


    const init = (function init() {

        const breakpointsSort = () => {
            //console.log(bp);
            var breakpoints_sort = {};
            var breakpoints_reverse = [];
            var values = [];
            for (let x in config.breakpoints) {
                //console.log(x, breakpoints[x]);
                values.push(config.breakpoints[x]);
                breakpoints_reverse[config.breakpoints[x]] = x;
            }
            values = values.sort(function (a, b) { return a - b; });
            if (!config.mobileFirst) values = values.reverse();
            // console.log(breakpoints_reverse);
            for (let x of values) {
                breakpoints_sort[breakpoints_reverse[x]] = x;
            }
            config.css = Object.assign({}, breakpoints_sort);
            return breakpoints_sort;
        }

        const getClassList = () => {
            const tags = document.querySelectorAll('[class*=":"]');
            let classList = '';
            tags.forEach(tag => {
                classList += tag.getAttribute('class') + ' ';
            });
            return classList;
        }

        const getAbbrList = () => {
            const regex = /[a-z_]+:([a-z0-9-%#]+(@[a-z]+)?;?)*/gi;
            return classList.match(regex).join(' ');
        }

        const cleanAbbrList = () => {
            classList = classList.split(' ')
            return Array.from(new Set(classList)).sort().join(' ');
        }

        const addAbbrSeparator = () => {
            return classList.replaceAll(' ', config.abbrSplitter);
        }

        const convertResponsiveAbbr = () => {
            var cpt = 0;
            let max = 1000;


            //process no media querry classes
            classList = classList.replaceAll(/([a-z_]+:[a-z0-9-%#]+)( |:|;|$)/gi, '$1@base$2');

            while (classList.search('@') >= 0 && cpt <= max) {
                //process single media query classes
                classList = classList.replaceAll(/([a-z_]+):([a-z0-9-%#]+)@([a-z]+);?(\s|$)/gi, '#0$3+$1:$2 ');
                //process multiple media query classes
                classList = classList.replaceAll(/([a-z_]+):((?:[a-z0-9-%#]+@[a-z]+;?)+)([a-z0-9-%#]+)@([a-z]+);?/gi, '#0$4+$1:$3+$1:$2');
                if (cpt++ >= max) {
                    console.log('maximum iteration reached')
                }
            }
            /*
            //remove remaining ';'
            classList = classList.replaceAll(';', ''); */

            return classList;
        }

        const convertCustomAbbr = () => {
            // remove undersocres
            classList = classList.replaceAll(/(_)([a-z_]+:)/gi, '$2');

            // convert (m|p)x: to (m|p)l & (m|p)r
            classList = classList.replaceAll(/_?(p|m)(?:x):([a-z0-9-%@]+)/gi, '$1l:$2+$1r:$2');

            // convert (m|p)y: to (m|p)t & (m|p)b
            classList = classList.replaceAll(/_?(p|m)(?:y):([a-z0-9-%%@]+)/gi, '$1t:$2+$1b:$2');

            return classList;
        }

        const convertCustomValues = () => {
            // colors (ie c:w to c:white)

            // console.log(classList);
            for (let x in config.colors) {
                const regex = new RegExp('([^\s+]*c:)(' + x + ')\\s', 'gi');
                //console.log(regex);
                classList = classList.replace(regex, '$1' + config.colors[x] + ' ');
            }
            // console.log(classList);

            // font-weight (ie fw:3 to fw:300)
            classList = classList.replace(/(fw:)([0-9])/gi, '$1$200');

            //console.log(classList);
            return classList;
        }

        const expandAbbr = () => {
            classList = expand(classList, { type: 'stylesheet' }).replaceAll(/\s/gi, ' ');

            // console.log(classList);
            // ff (ie ff:0 to ff:Arial)
            for (let x in config.fonts) {
                //console.log(x);
                const regex = new RegExp('(font-family: )(' + x + ')(px)?', 'gi');
                // console.log(regex);
                classList = classList.replace(regex, '$1' + config.fonts[x]);
            }
            // console.log(classList);
            return classList;
        }

        const getSelectorList = () => {
            return classList;
        }

        const escapeSelectorList = () => {
            // add dot to selector
            selectorList = selectorList.replaceAll(/([a-z_]+:)/gi, `.$1`);

            // escape special chars
            selectorList = selectorList.replaceAll(/([^a-z0-9_.\ ])/gi, `\\$1`);
            return selectorList;
        }

        const detectHoverSelectors = () => {
            selectorList = selectorList.replaceAll(/(_[a-z_\\]+:[^\s]+)/gi, `$1:hover`);
            return selectorList;
        }

        const addSelectors = () => {
            const selectorArray = selectorList.split(' ');
            const classArray = classList.split(' ' + expand(config.abbrSplitter, { type: 'stylesheet' }) + ' ');
            // console.log(selectorArray);
            // console.log(classArray);

            // clean config.css
            for (let x in config.css) {
                config.css[x] = '';
            }

            for (let x = 0; x < selectorArray.length; x++) {
                const selector = selectorArray[x];
                // console.log(classArray[x]);
                for (let property of classArray[x].matchAll(/#0+([_a-z]+)\s((?:[a-z-]+:\s[a-z0-9-%#'' ]+;\s?)+)/gi)) {
                    // console.log(`${property[1]} : ${selector}{${property[2]}}`);
                    config.css[property[1]] = (config.css[property[1]] || '') + `${selector}{${property[2]}}`;
                }
                // console.log('');
            }
            // console.log(config.css);

            return classList;
        }

        const output = () => {
            // const selectorArray = selectorList.split(' ');
            // const classArray = classList.split(' ' + expand(config.abbrSplitter, { type: 'stylesheet' }) + ' ');
            // console.log(selectorArray);
            // console.log(classArray);
            const body = document.body;
            let output = `${config.css['base']}`;
            for (let x in config.breakpoints) {
                if (x !== 'base') {
                    const direction = (config.mobileFirst)? 'min-width':'max-width';
                    output += `@media (${direction}:${config.breakpoints[x]}px){${config.css[x]}}`;
                    // console.log(output);
                }
            }
            if (config.output !== output) {
                config.output = output;
                const $style = (document.querySelectorAll('#mimeticss').length == 0) ? document.createElement('style') : document.querySelectorAll('#mimeticss')[0];
                $style.innerText = '';
                $style.setAttribute('type', 'text/css');
                $style.setAttribute('id', 'mimeticss');
                $style.innerText = output;
                if (document.querySelectorAll('#mimeticss').length == 0) body.append($style);
            }
        }



        let classList;
        let selectorList;

        config.breakpoints = breakpointsSort();
        // console.log(config.breakpoints);

        classList = getClassList();
        // console.log(classList);
        classList = getAbbrList();
        // console.log(classList);
        classList = cleanAbbrList();
        // console.log(classList);
        selectorList = getSelectorList();
        // console.log(selectorList);
        selectorList = escapeSelectorList();
        // console.log(selectorList);
        selectorList = detectHoverSelectors();
        // console.log(selectorList.split(' '));
        classList = convertResponsiveAbbr();
        // console.log(classList);
        classList = convertCustomAbbr();
        // console.log(classList);
        classList = convertCustomValues();
        // console.log(classList);
        classList = addAbbrSeparator();
        // console.log(classList);
        classList = expandAbbr();
        // console.log(classList);
        classList = addSelectors();
        // console.log(classList);

        output();
        return init;

    })();
}

mimeticss({
    colors: {
        0: 'black',

    },
		breakpoints: {
				base: '',
				xl: 1399,
				lg: 1199,
				md: 991,
				sm: 767,
				xs: 575
		},
		fonts: {
				0: 'icon',

		},
    mobileFirst: false
});
