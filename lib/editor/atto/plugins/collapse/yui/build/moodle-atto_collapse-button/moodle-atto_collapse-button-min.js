YUI.add("moodle-atto_collapse-button",function(e,t){M.atto_collapse=M.atto_collapse||{init:function(t){var n=function(t,n){t.preventDefault(),e.one("#"+n+"_toolbar").toggleClass("collapsetoolbar")};M.editor_atto.add_toolbar_button(t.elementid,"collapse",t.icon,t.group,n),e.one("#"+t.elementid+"_toolbar").toggleClass("collapsetoolbar")}}},"@VERSION@",{requires:["node"]});