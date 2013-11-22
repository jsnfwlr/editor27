<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Strings for component 'filter_mathjax', language 'en'.
 *
 * Note: use filter/mathjax/cli/update_lang_files.php script to import strings from upstream JS lang files.
 *
 * @package    filter_mathjax
 * @copyright  1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


//== Custom Moodle strings that are not part of upstream MathJax ==
$string['filtername'] = 'MathJax';


// == MathJax upstream lang strings from all standard upstream domains ==
$strings['FontWarnings:webFont'] = "MathJax is using web-based fonts to display the mathematics on this page.  These take time to download, so the page would render faster if you installed math fonts directly in your system's font folder.";
$strings['FontWarnings:imageFonts'] = "MathJax is using its image fonts rather than local or web-based fonts. This will render slower than usual, and the mathematics may not print at the full resolution of your printer.";
$strings['FontWarnings:noFonts'] = "MathJax is unable to locate a font to use to display its mathematics, and image fonts are not available, so it is falling back on generic unicode characters in hopes that your browser will be able to display them.  Some characters may not show up properly, or possibly not at all.";
$strings['FontWarnings:webFonts'] = "Most modern browsers allow for fonts to be downloaded over the web. Updating to a more recent version of your browser (or changing browsers) could improve the quality of the mathematics on this page.";
$strings['FontWarnings:fonts'] = "MathJax can use either the [STIX fonts](%1) or the [MathJax TeX fonts](%2).  Download and install one of those fonts to improve your MathJax experience.";
$strings['FontWarnings:STIXPage'] = "This page is designed to use the [STIX fonts](%1).  Download and install those fonts to improve your MathJax experience.";
$strings['FontWarnings:TeXPage'] = "This page is designed to use the [MathJax TeX fonts](%1).  Download and install those fonts to improve your MathJax experience.";
$strings['MathMenu:Show'] = "Show Math As";
$strings['MathMenu:MathMLcode'] = "MathML Code";
$strings['MathMenu:OriginalMathML'] = "Original MathML";
$strings['MathMenu:TeXCommands'] = "TeX Commands";
$strings['MathMenu:AsciiMathInput'] = "AsciiMathML input";
$strings['MathMenu:Original'] = "Original Form";
$strings['MathMenu:ErrorMessage'] = "Error Message";
$strings['MathMenu:Annotation'] = "Annotation";
$strings['MathMenu:TeX'] = "TeX";
$strings['MathMenu:StarMath'] = "StarMath";
$strings['MathMenu:Maple'] = "Maple";
$strings['MathMenu:ContentMathML'] = "Content MathML";
$strings['MathMenu:OpenMath'] = "OpenMath";
$strings['MathMenu:texHints'] = "Show TeX hints in MathML";
$strings['MathMenu:Settings'] = "Math Settings";
$strings['MathMenu:ZoomTrigger'] = "Zoom Trigger";
$strings['MathMenu:Hover'] = "Hover";
$strings['MathMenu:Click'] = "Click";
$strings['MathMenu:DoubleClick'] = "Double-Click";
$strings['MathMenu:NoZoom'] = "No Zoom";
$strings['MathMenu:TriggerRequires'] = "Trigger Requires'] = ";
$strings['MathMenu:Option'] = "Option";
$strings['MathMenu:Alt'] = "Alt";
$strings['MathMenu:Command'] = "Command";
$strings['MathMenu:Control'] = "Control";
$strings['MathMenu:Shift'] = "Shift";
$strings['MathMenu:ZoomFactor'] = "Zoom Factor";
$strings['MathMenu:Renderer'] = "Math Renderer";
$strings['MathMenu:MPHandles'] = "Let MathPlayer Handle'] = ";
$strings['MathMenu:MenuEvents'] = "Menu Events";
$strings['MathMenu:MouseEvents'] = "Mouse Events";
$strings['MathMenu:MenuAndMouse'] = "Mouse and Menu Events";
$strings['MathMenu:FontPrefs'] = "Font Preferences";
$strings['MathMenu:ForHTMLCSS'] = "For HTML-CSS'] = ";
$strings['MathMenu:Auto'] = "Auto";
$strings['MathMenu:TeXLocal'] = "TeX (local)";
$strings['MathMenu:TeXWeb'] = "TeX (web)";
$strings['MathMenu:TeXImage'] = "TeX (image)";
$strings['MathMenu:STIXLocal'] = "STIX (local)";
$strings['MathMenu:STIXWeb'] = "STIX (web)";
$strings['MathMenu:AsanaMathWeb'] = "Asana Math (web)";
$strings['MathMenu:GyrePagellaWeb'] = "Gyre Pagella (web)";
$strings['MathMenu:GyreTermesWeb'] = "Gyre Termes (web)";
$strings['MathMenu:LatinModernWeb'] = "Latin Modern (web)";
$strings['MathMenu:NeoEulerWeb'] = "Neo Euler (web)";
$strings['MathMenu:ContextMenu'] = "Contextual Menu";
$strings['MathMenu:Browser'] = "Browser";
$strings['MathMenu:Scale'] = "Scale All Math ...";
$strings['MathMenu:Discoverable'] = "Highlight on Hover";
$strings['MathMenu:Locale'] = "Language";
$strings['MathMenu:LoadLocale'] = "Load from URL ...";
$strings['MathMenu:About'] = "About MathJax";
$strings['MathMenu:Help'] = "MathJax Help";
$strings['MathMenu:localTeXfonts'] = "using local TeX fonts";
$strings['MathMenu:webTeXfonts'] = "using web TeX font";
$strings['MathMenu:imagefonts'] = "using Image fonts";
$strings['MathMenu:localSTIXfonts'] = "using local STIX fonts";
$strings['MathMenu:webSVGfonts'] = "using web SVG fonts";
$strings['MathMenu:genericfonts'] = "using generic unicode fonts";
$strings['MathMenu:wofforotffonts'] = "woff or otf fonts";
$strings['MathMenu:eotffonts'] = "eot fonts";
$strings['MathMenu:svgfonts'] = "svg fonts";
$strings['MathMenu:WebkitNativeMMLWarning'] = "Your browser doesn't seem to support MathML natively, so switching to MathML output may cause the mathematics on the page to become unreadable.";
$strings['MathMenu:MSIENativeMMLWarning'] = "Internet Explorer requires the MathPlayer plugin in order to process MathML output.";
$strings['MathMenu:OperaNativeMMLWarning'] = "Opera's support for MathML is limited, so switching to MathML output may cause some expressions to render poorly.";
$strings['MathMenu:SafariNativeMMLWarning'] = "Your browser's native MathML does not implement all the features used by MathJax, so some expressions may not render properly.";
$strings['MathMenu:FirefoxNativeMMLWarning'] = "Your browser's native MathML does not implement all the features used by MathJax, so some expressions may not render properly.";
$strings['MathMenu:MSIESVGWarning'] = "SVG is not implemented in Internet Explorer prior to IE9 or when it is emulating IE8 or below. Switching to SVG output will cause the mathematics to not display properly.";
$strings['MathMenu:LoadURL'] = "Load translation data from this URL'] = ";
$strings['MathMenu:BadURL'] = "The URL should be for a javascript file that defines MathJax translation data.  Javascript file names should end with '.js'";
$strings['MathMenu:BadData'] = "Failed to load translation data from %1";
$strings['MathMenu:SwitchAnyway'] = "Switch the renderer anyway?\n\n(Press OK to switch, CANCEL to continue with the current renderer)";
$strings['MathMenu:ScaleMath'] = "Scale all mathematics (compared to surrounding text) by";
$strings['MathMenu:NonZeroScale'] = "The scale should not be zero";
$strings['MathMenu:PercentScale'] = "The scale should be a percentage (e.g., 120%%)";
$strings['MathMenu:IE8warning'] = "This will disable the MathJax menu and zoom features, but you can Alt-Click on an expression to obtain the MathJax menu instead.\n\nReally change the MathPlayer settings?";
$strings['MathMenu:IE9warning'] = "The MathJax contextual menu will be disabled, but you can Alt-Click on an expression to obtain the MathJax menu instead.";
$strings['MathMenu:NoOriginalForm'] = "No original form available";
$strings['MathMenu:Close'] = "Close";
$strings['MathMenu:EqSource'] = "MathJax Equation Source";
$strings['TeX:ExtraOpenMissingClose'] = "Extra open brace or missing close brace";
$strings['TeX:ExtraCloseMissingOpen'] = "Extra close brace or missing open brace";
$strings['TeX:MissingLeftExtraRight'] = "Missing \\left or extra \\right";
$strings['TeX:MissingScript'] = "Missing superscript or subscript argument";
$strings['TeX:ExtraLeftMissingRight'] = "Extra \\left or missing \\right";
$strings['TeX:Misplaced'] = "Misplaced %1";
$strings['TeX:MissingOpenForSub'] = "Missing open brace for subscript";
$strings['TeX:MissingOpenForSup'] = "Missing open brace for superscript";
$strings['TeX:AmbiguousUseOf'] = "Ambiguous use of %1";
$strings['TeX:EnvBadEnd'] = "\\begin{%1} ended with \\end{%2}";
$strings['TeX:EnvMissingEnd'] = "Missing \\end{%1}";
$strings['TeX:MissingBoxFor'] = "Missing box for %1";
$strings['TeX:MissingCloseBrace'] = "Missing close brace";
$strings['TeX:UndefinedControlSequence'] = "Undefined control sequence %1";
$strings['TeX:DoubleExponent'] = "Double exponent: use braces to clarify";
$strings['TeX:DoubleSubscripts'] = "Double subscripts: use braces to clarify";
$strings['TeX:DoubleExponentPrime'] = "Prime causes double exponent: use braces to clarify";
$strings['TeX:CantUseHash1'] = "You can't use 'macro parameter character #' in math mode";
$strings['TeX:MisplacedMiddle'] = "%1 must be within \\left and \\right";
$strings['TeX:MisplacedLimits'] = "%1 is allowed only on operators";
$strings['TeX:MisplacedMoveRoot'] = "%1 can appear only within a root";
$strings['TeX:MultipleCommand'] = "Multiple %1";
$strings['TeX:IntegerArg'] = "The argument to %1 must be an integer";
$strings['TeX:NotMathMLToken'] = "%1 is not a token element";
$strings['TeX:InvalidMathMLAttr'] = "Invalid MathML attribute: %1";
$strings['TeX:UnknownAttrForElement'] = "%1 is not a recognized attribute for %2";
$strings['TeX:MaxMacroSub1'] = "MathJax maximum macro substitution count exceeded; is there a recursive macro call?";
$strings['TeX:MaxMacroSub2'] = "MathJax maximum substitution count exceeded; is there a recursive latex environment?";
$strings['TeX:MissingArgFor'] = "Missing argument for %1";
$strings['TeX:ExtraAlignTab'] = "Extra alignment tab in \\cases text";
$strings['TeX:BracketMustBeDimension'] = "Bracket argument to %1 must be a dimension";
$strings['TeX:InvalidEnv'] = "Invalid environment name '%1'";
$strings['TeX:UnknownEnv'] = "Unknown environment '%1'";
$strings['TeX:ExtraCloseLooking'] = "Extra close brace while looking for %1";
$strings['TeX:MissingCloseBracket'] = "Couldn't find closing ']' for argument to %1";
$strings['TeX:MissingOrUnrecognizedDelim'] = "Missing or unrecognized delimiter for %1";
$strings['TeX:MissingDimOrUnits'] = "Missing dimension or its units for %1";
$strings['TeX:TokenNotFoundForCommand'] = "Couldn't find %1 for %2";
$strings['TeX:MathNotTerminated'] = "Math not terminated in text box";
$strings['TeX:IllegalMacroParam'] = "Illegal macro parameter reference";
$strings['TeX:MaxBufferSize'] = "MathJax internal buffer size exceeded; is there a recursive macro call?";
$strings['TeX:CommandNotAllowedInEnv'] = "%1 not allowed in %2 environment";
$strings['TeX:MultipleLabel'] = "Label '%1' multiply defined";
$strings['TeX:CommandAtTheBeginingOfLine'] = "%1 must come at the beginning of the line";
$strings['TeX:IllegalAlign'] = "Illegal alignment specified in %1";
$strings['TeX:BadMathStyleFor'] = "Bad math style for %1";
$strings['TeX:PositiveIntegerArg'] = "Argument to %1 must be a positive integer";
$strings['TeX:ErroneousNestingEq'] = "Erroneous nesting of equation structures";
$strings['TeX:MultlineRowsOneCol'] = "The rows within the %1 environment must have exactly one column";
$strings['TeX:MultipleBBoxProperty'] = "%1 specified twice in %2";
$strings['TeX:InvalidBBoxProperty'] = "'%1' doesn't look like a color, a padding dimension, or a style";
$strings['TeX:ExtraEndMissingBegin'] = "Extra %1 or missing \\begingroup";
$strings['TeX:GlobalNotFollowedBy'] = "%1 not followed by \\let, \\def, or \\newcommand";
$strings['TeX:UndefinedColorModel'] = "Color model '%1' not defined";
$strings['TeX:ModelArg1'] = "Color values for the %1 model require 3 numbers";
$strings['TeX:InvalidDecimalNumber'] = "Invalid decimal number";
$strings['TeX:ModelArg2'] = "Color values for the %1 model must be between %2 and %3";
$strings['TeX:InvalidNumber'] = "Invalid number";
$strings['TeX:NewextarrowArg1'] = "First argument to %1 must be a control sequence name";
$strings['TeX:NewextarrowArg2'] = "Second argument to %1 must be two integers separated by a comma";
$strings['TeX:NewextarrowArg3'] = "Third argument to %1 must be a unicode character number";
$strings['TeX:NoClosingChar'] = "Can't find closing %1";
$strings['TeX:IllegalControlSequenceName'] = "Illegal control sequence name for %1";
$strings['TeX:IllegalParamNumber'] = "Illegal number of parameters specified in %1";
$strings['TeX:MissingCS'] = "%1 must be followed by a control sequence";
$strings['TeX:CantUseHash2'] = "Illegal use of # in template for %1";
$strings['TeX:SequentialParam'] = "Parameters for %1 must be numbered sequentially";
$strings['TeX:MissingReplacementString'] = "Missing replacement string for definition of %1";
$strings['TeX:MismatchUseDef'] = "Use of %1 doesn't match its definition";
$strings['TeX:RunawayArgument'] = "Runaway argument for %1?";
$strings['TeX:NoClosingDelim'] = "Can't find closing delimiter for %1";
$strings['en:CookieConfig'] = "MathJax has found a user-configuration cookie that includes code to be run. Do you want to run it?\n\n(You should press Cancel unless you set up the cookie yourself.)";
$strings['en:MathProcessingError'] = "Math Processing Error";
$strings['en:MathError'] = "Math Error";
$strings['en:LoadFile'] = "Loading %1";
$strings['en:Loading'] = "Loading";
$strings['en:LoadFailed'] = "File failed to load: %1";
$strings['en:ProcessMath'] = "Processing Math: %1%%";
$strings['en:Processing'] = "Processing";
$strings['en:TypesetMath'] = "Typesetting Math: %1%%";
$strings['en:Typesetting'] = "Typesetting";
$strings['en:MathJaxNotSupported'] = "Your browser does not support MathJax";
$strings['HTML-CSS:LoadWebFont'] = "Loading web-font %1";
$strings['HTML-CSS:CantLoadWebFont'] = "Can't load web font %1";
$strings['HTML-CSS:FirefoxCantLoadWebFont'] = "Firefox can't load web fonts from a remote host";
$strings['HTML-CSS:CantFindFontUsing'] = "Can't find a valid font using %1";
$strings['HTML-CSS:WebFontsNotAvailable'] = "Web-Fonts not available -- using image fonts instead";
$strings['MathML:BadMglyph'] = "Bad mglyph: %1";
$strings['MathML:BadMglyphFont'] = "Bad font: %1";
$strings['MathML:MathPlayer'] = "MathJax was not able to set up MathPlayer.\n\nIf MathPlayer is not installed, you need to install it first.\nOtherwise, your security settings may be preventing ActiveX     \ncontrols from running.  Use the Internet Options item under\nthe Tools menu and select the Security tab, then press the\nCustom Level button. Check that the settings for\n'Run ActiveX Controls', and 'Binary and script behaviors'\nare enabled.\n\nCurrently you will see error messages rather than\ntypeset mathematics.";
$strings['MathML:CantCreateXMLParser'] = "MathJax can't create an XML parser for MathML.  Check that\nthe 'Script ActiveX controls marked safe for scripting' security\nsetting is enabled (use the Internet Options item in the Tools\nmenu, and select the Security panel, then press the Custom Level\nbutton to check this).\n\nMathML equations will not be able to be processed by MathJax.";
$strings['MathML:UnknownNodeType'] = "Unknown node type: %1";
$strings['MathML:UnexpectedTextNode'] = "Unexpected text node: %1";
$strings['MathML:ErrorParsingMathML'] = "Error parsing MathML";
$strings['MathML:ParsingError'] = "Error parsing MathML: %1";
$strings['MathML:MathMLSingleElement'] = "MathML must be formed by a single element";
$strings['MathML:MathMLRootElement'] = "MathML must be formed by a \u003Cmath\u003E element, not %1";
$strings['HelpDialog:Help'] = "MathJax Help";
$strings['HelpDialog:MathJax'] = "*MathJax* is a JavaScript library that allows page authors to include mathematics within their web pages.  As a reader, you don't need to do anything to make that happen.";
$strings['HelpDialog:Browsers'] = "*Browsers*: MathJax works with all modern browsers including IE6+, Firefox 3+, Chrome 0.2+, Safari 2+, Opera 9.6+ and most mobile browsers.";
$strings['HelpDialog:Menu'] = "*Math Menu*: MathJax adds a contextual menu to equations.  Right-click or CTRL-click on any mathematics to access the menu.";
$strings['HelpDialog:ShowMath'] = "*Show Math As* allows you to view the formula's source markup for copy \u0026 paste (as MathML or in its original format).";
$strings['HelpDialog:Settings'] = "*Settings* gives you control over features of MathJax, such as the size of the mathematics, and the mechanism used to display equations.";
$strings['HelpDialog:Language'] = "*Language* lets you select the language used by MathJax for its menus and warning messages.";
$strings['HelpDialog:Zoom'] = "*Math Zoom*: If you are having difficulty reading an equation, MathJax can enlarge it to help you see it better.";
$strings['HelpDialog:Accessibilty'] = "*Accessibility*: MathJax will automatically work with screen readers to make mathematics accessible to the visually impaired.";
$strings['HelpDialog:Fonts'] = "*Fonts*: MathJax will use certain math fonts if they are installed on your computer; otherwise, it will use web-based fonts.  Although not required, locally installed fonts will speed up typesetting.  We suggest installing the [STIX fonts](%1).";
