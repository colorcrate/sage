/**
 *  JS Helpers
 *  @version 0.0.4
 *
 *  This file contains various useful JS functions to help you perform common
 *  tasks. This file must be imported into your other files in order for these
 *  functions to be used. Example:
 *
 *  In your JS file:
 *
 *  import { isAdmin, isGutenbergActive } from '../../helpers';
 *
 *  console.log( isAdmin() );
 */

export {
  // 1.0 Animation
  slideDown,
  slideUp,

  // 2.0 WordPress
  isAdmin,
  isGutenbergActive,

  // 3.0 CSS
  matrixToObject,
  objectToMatrix,

  // 4.0 Math
  ease,
  percentBetween,
  randomNumberBetween,
  valueBetween,

  // 5.0 Window / DOM
  documentHeight,
  scrollPercent
}


//
// 1.0 ANIMATION
//

/**
 * slideDown
 * @since 0.0.2
 *
 * Fluidly animates an elment's height to its native height to
 * 'slide down' from zero height (equivalent to jQuery's slideDown function,
 * most often used after an element has had 'slideUp' applied).
 *
 * @param {DOM element} target A DOM element to animate
 * @param {number} duration Animation duration in milliseconds
 *
 */
const slideDown = ( target, duration = 500 ) => {
  target.style.removeProperty('display');
  let display = window.getComputedStyle( target ).display;
  if ( display === 'none' ) {
    display = 'block';
  }
  target.style.display = display;
  let height = target.offsetHeight;
  target.style.overflow = 'hidden';
  target.style.height = 0;
  target.style.paddingTop = 0;
  target.style.paddingBottom = 0;
  target.style.marginTop = 0;
  target.style.marginBottom = 0;
  target.offsetHeight;
  target.style.boxSizing = 'border-box';
  target.style.transitionProperty = "height, margin, padding";
  target.style.transitionDuration = duration + 'ms';
  target.style.height = height + 'px';
  target.style.removeProperty('padding-top');
  target.style.removeProperty('padding-bottom');
  target.style.removeProperty('margin-top');
  target.style.removeProperty('margin-bottom');
  window.setTimeout( () => {
    target.style.removeProperty('height');
    target.style.removeProperty('overflow');
    target.style.removeProperty('transition-duration');
    target.style.removeProperty('transition-property');
  }, duration);
}

/**
 * slideUp
 * @since 0.0.2
 *
 * Fluidly animates an element's height to zero so it appears to
 * 'slide up' and into nothingness (equivalent to jQuery's slideUp function)
 *
 * @param {DOM element} target DOM element to animate
 * @param {number} duration Animation duration in milliseconds
 */
const slideUp = ( target, duration = 500 ) => {
  target.style.transitionProperty = 'height, margin, padding';
  target.style.transitionDuration = duration + 'ms';
  target.style.boxSizing = 'border-box';
  target.style.height = target.offsetHeight + 'px';
  target.offsetHeight;
  target.style.overflow = 'hidden';
  target.style.height = 0;
  target.style.paddingTop = 0;
  target.style.paddingBottom = 0;
  target.style.marginTop = 0;
  target.style.marginBottom = 0;
  window.setTimeout( () => {
    target.style.display = 'none';
    target.style.removeProperty('height');
    target.style.removeProperty('padding-top');
    target.style.removeProperty('padding-bottom');
    target.style.removeProperty('margin-top');
    target.style.removeProperty('margin-bottom');
    target.style.removeProperty('overflow');
    target.style.removeProperty('transition-duration');
    target.style.removeProperty('transition-property');
  }, duration);
}

//
// 2.0 WordPress
//

/**
 * Is admin?
 * @since 0.0.0
 *
 * Checks if we're viewing a WP admin screen.
 *
 * @return {boolean}
 */
const isAdmin = () => {
  return document.body.classList.contains('wp-admin');
}

/**
 *  Is Gutenberg Active?
 *  @since 0.0.0
 *
 *  Checks if the Gutenberg editor's initailization is complete. This is often
 *  called on an interval to await executing other functions until Gutenberg
 *  is fully loaded.
 *
 *  @return boolean
 */
const isGutenbergActive = () => {
  return typeof wp !== 'undefined' && typeof wp.blocks !== 'undefined';
}


//
// 3.0 CSS
//

/**
 * Matrix to object
 * @since 0.0.1
 *
 * When you get an elements CSS transform styles via getComputedStyle(),
 * the results are returned as a matrix. This function converts the
 * matrix string to a JS object.
 * Eg.
 *   matrixToObject( 'matrix(1, 0, 0, 1, -779, -625)' )
 *     returns { scaleX: 1, skewY: 0, skewX: 0, scaleY: 1, translateX: -779, translateY: -625 }
 *
 * @param {string} matrix A CSS transform matrix string
 *
 * @return {object} The matrix as an object form
 */
const matrixToObject = ( matrix ) => {
  matrix = matrix.replace( 'matrix(', '' ).replace(')', '').replace(/[ ]/g, '').split(',');
  return {
    scaleX: matrix[0],
    skewY: matrix[1],
    skewX: matrix[2],
    scaleY: matrix[3],
    translateX: matrix[4],
    translateY: matrix[5]
  }
};

/**
 *  Object to matrix
 *  @since 0.0.1
 *
 *  This function takes a matrix object produced by matrixToObject() and
 *  converts it back into a CSS transform matrix string.
 *  Eg.
 *    objectToMatrix( { scaleX: 1, skewY: 0, skewX: 0, scaleY: 1, translateX: -779, translateY: -625 } )
 *      returns 'matrix(1, 0, 0, 1, -779, -625)'
 *
 *  @param matrix object - A matrix object, usually created by matrixToObject()
 *
 *  @return string       - A CSS transform matrix string
 */
const objectToMatrix = ( obj ) => {
  return 'matrix(' + obj.scaleX + ', ' + obj.skewY + ', ' + obj.skewX + ', ' + obj.scaleY + ', ' + obj.translateX + ', ' + obj.translateY + ')';
};


//
// 4.0 Math
//

/**
 * Ease
 * @since 0.0.1
 *
 * Given a number x between 0 and 1, eases the value based on a selected
 * easing function.
 *
 * @param {boolean} x A number between 0 and 1 to ease
 * @param {string} type Default 'cubic' – The type of easing function to use
 *
 * @return {number} Returns an eased value between 0 and 1
 */
const ease = ( x, type = 'cubic' ) => {
  // Eases a value between 0 and 1. See: https://easings.net/
  if ( type === 'cubic' ) { // easeInOutCubic
    return x < 0.5 ? 4 * x * x * x : 1 - Math.pow(-2 * x + 2, 3) / 2;
  }
  if ( type === 'circ' ) { // easeInOutCirc
    return x < 0.5 ? (1 - Math.sqrt(1 - Math.pow(2 * x, 2))) / 2 : (Math.sqrt(1 - Math.pow(-2 * x + 2, 2)) + 1) / 2;
  }
  if ( type === 'quart' ) { // easeInOutQuart
    return x < 0.5 ? 8 * x * x * x * x : 1 - Math.pow(-2 * x + 2, 4) / 2;
  }
};

/**
 * Percent Between
 * @since 0.0.3
 *
 * Given a number value, returns a decimal percent for how far that number is
 * "between" a start and end number. For example, if:
 *
 * value = 75
 * start = 50
 * end = 100
 * --> returns 0.5 (because 75 is 50% of the way between 50 and 100)
 *
 * Will not return numbers greater than 1 or less than 0.
 *
 * @param {number} value The value to check
 * @param {number} start The start of the range
 * @param {numbrer} end The end of the range
 *
 * @returns {number} Decimal percentage between 0 and 1
 */
const percentBetween = ( value, start, end ) => {
  // Error catch, return null if values weren't passed
  if ( typeof value === 'undefined' ||  typeof start === 'undefined' || typeof end === 'undefined' ) {
    return null;
  }

  let percent = null;
  if ( value <= start ) {
    // If we're below our start value, return 0%
    percent = 0;
  }
  else if ( value > start && value < end ) {
    percent = 1 - ( value - end ) / ( start - end );
  }
  else if ( value >= end ) {
    // If we're above our end value, return 100%
    percent = 1;
  }
  return percent;
};

/**
 * Random Number Between
 * @since 0.0.1
 *
 * Returns a random whole number between two given numbers
 *
 * @param {number} min The minimum number
 * @param {number} max The maximum number
 *
 * @return {number} A random number between min and max
 */
const randomNumberBetween = ( min, max ) => {
  return Math.random() * ( max - min ) + min;
}

/**
 * Value Between
 * @since 0.0.3
 *
 * Given a range of numbers defined by 'start' and 'end',
 * finds the value that is 'percent' of the way between the two numbers.
 * For example, if:
 *
 * start = 0
 * end = 200
 * percent = 0.5
 * -> returns 100 (because 100 is 50% of the way between 0 and 100)
 *
 * @param {number} percent Decimal value between 0 and 1
 * @param {number} start The number that starts the range
 * @param {number} end The number that ends the range
 *
 * @returns {number} The number that is the percent of the way between the two numbers
 */
const valueBetween = ( percent, start, end ) => {
  return start - ( ( start - end ) * percent );
};


//
// 5.0 Window / DOM
//

/**
 * Get Doc Height
 * @since 0.0.3
 *
 * Returns the document height. These numbers vary from browser to browser, so
 * we get them all and return the biggest number just to be safe!
 *
 * @returns {number} The document height in pixels
 */
const documentHeight = () => {
  const body = document.body;
  const html = document.documentElement;
  return Math.max( body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight );
};

/**
 * Scroll Percent
 * @since 0.0.3
 *
 * Returns what decimal percentage of the page the user has scrolled down.
 *
 * @returns {number} A decimal percentage between 0 and 1
 */
const scrollPercent = () => {
  const min = window.innerHeight;
  const max = documentHeight();
  return window.scrollY / ( max - min );
};
