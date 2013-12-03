Description of MathJAX library integration in Moodle
=========================================================================================

License: Apache 2.0
Source: http://www.mathjax.org

Moodle maintainer: Damyon Wiese

=========================================================================================
Upgrade procedure:

1/ extract standard MathJax package into filter/mathjax/mathjax
2/ bump up main version.php
3/ update ./thirdpartylibs.xml
4/ execute cli/update_lang_files.php and review changes in lang/en/filter_mathjax.php
5/ delete (large) unused directories /filter/mathjax/mathjax/uncompressed + /filter/mathjax/mathjax/test + /filter/mathjax/mathjax/fonts/HTML-CSS/Tex/png

