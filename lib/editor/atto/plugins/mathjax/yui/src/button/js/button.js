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
 * Atto text editor mathjax plugin.
 *
 * @package    editor-atto
 * @copyright  2013 Damyon Wiese  <damyon@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
M.atto_mathjax = M.atto_mathjax || {
    /**
     * The window used to get the mathjax details.
     *
     * @property dialogue
     * @type M.core.dialogue
     * @default null
     */
    dialogue : null,

    /**
     * The window used to get the mathjax details.
     *
     * @property librarydialogue
     * @type M.core.dialogue
     * @default null
     */
    librarydialogue : null,

    /**
     * The selection object returned by the browser.
     *
     * @property selection
     * @type Range
     * @default null
     */
    selection : null,

    /**
     * The selection object returned by the browser.
     *
     * @property selection
     * @type Range
     * @default null
     */
    equationlibrary : [ '\\frac{a}{b}',
                        '\\sqrt[a]{b}',
                        '\\sum_{a}^{b}{c}',
                        '{a}^{b}',
                        '{a}_{b}',
                        '\\int_{a}^{b} {c}',
                         '\\lim_{a \\rightarrow b}',
                        'a\'',
                        '\\alpha',
                        '\\beta',
                        '\\pi',
                        '=',
                        '\\approx',
                        '\\neq',
                        '\\leq',
                        '<',
                        '\\geq',
                        '>'],

    /**
     * Display the equation dialogue.
     *
     * @method init
     * @param Event e
     * @param string elementid
     */
    display_equation : function(e, elementid) {
        e.preventDefault();
        if (!M.editor_atto.is_active(elementid)) {
            M.editor_atto.focus(elementid);
        }
        M.atto_mathjax.selection = M.editor_atto.get_selection();
        if (M.atto_mathjax.selection !== false) {
            var dialogue;
            if (!M.atto_mathjax.dialogue) {
                dialogue = new M.core.dialogue({
                    visible: false,
                    modal: true,
                    close: true,
                    draggable: true
                });
            } else {
                dialogue = M.atto_mathjax.dialogue;
            }

            dialogue.render();
            dialogue.set('bodyContent', M.atto_mathjax.get_form_content(elementid));
            dialogue.set('headerContent', M.util.get_string('editequation', 'atto_mathjax'));

            M.atto_mathjax.load_equation();

            dialogue.show();
            MathJax.Hub.Queue(["Typeset",MathJax.Hub,"atto_mathjax_preview"]);

            M.atto_mathjax.dialogue = dialogue;
        }
    },

    /**
     * Add this button to the form.
     *
     * @method init
     * @param {Object} params
     */
    init : function(params) {
        M.editor_atto.add_toolbar_button(params.elementid, 'mathjax', params.icon, params.group, this.display_equation, this);
    },

    /**
     * If there is selected text and it is part of an equation,
     * extract the equation and set it in the form.
     *
     * @method load_equation
     */
    load_equation : function() {
        // Find the equation in the surrounding text.
        var selectednode = M.editor_atto.get_selection_parent_node(),
            text,
            pattern,
            equation;

        // Note this is a document fragment and YUI doesn't like them.
        if (!selectednode) {
            return;
        }

        text = Y.one(selectednode).get('text');
        pattern = /\$\$.*\$\$/;
        equation = pattern.exec(text);
        if (equation) {
            equation = equation[0].replace(/\$\$/g, '');

            Y.one('#atto_mathjax_equation').set('text', equation);
            Y.one('#atto_mathjax_preview').set('text', '$$' + equation + '$$');
        }
    },

    /**
     * The OK button has been pressed - make the changes to the source.
     *
     * @method set_equation
     * @param Event e
     */
    update_preview : function(e) {
        var equation = Y.one(e.currentTarget).get('value');

        var preview = null;
        MathJax.Hub.Queue(function () {
            preview = MathJax.Hub.getAllJax("atto_mathjax_preview")[0];
        });
        MathJax.Hub.Queue(["Text",preview,equation]);
    },

    /**
     * The OK button has been pressed - make the changes to the source.
     *
     * @method set_equation
     * @param Event e
     */
    set_equation : function(e, elementid) {
        var input,
            selectednode,
            text,
            pattern,
            equation,
            value;

        e.preventDefault();
        M.atto_mathjax.dialogue.hide();
        M.editor_atto.set_selection(M.atto_mathjax.selection);

        input = e.currentTarget.ancestor('.atto_form').one('textarea');

        value = input.get('value');
        if (value !== '') {
            value = '$$ ' + value.trim() + ' $$';
            selectednode = Y.one(M.editor_atto.get_selection_parent_node()),
            text = selectednode.get('text');
            pattern = /\$\$.*\$\$/;
            equation = pattern.exec(text);
            if (equation) {
                // Replace the equation.
                text = text.replace(equation, '$$' + value + '$$');
                selectednode.set('text', text);
            } else {
                // Insert the new equation.
                if (document.selection && document.selection.createRange().pasteHTML) {
                    document.selection.createRange().pasteHTML(value);
                } else {
                    document.execCommand('insertHTML', false, value);
                }

            }

            // Clean the YUI ids from the HTML.
            M.editor_atto.text_updated(elementid);
        }
    },

    /**
     * Open a non modal popup with a list of example latex commands.
     *
     * @method view_library
     * @param Event e
     */
    view_library : function(e) {
        var dialogue;
        e.preventDefault();

        if (!M.atto_mathjax.librarydialogue) {
            dialogue = new M.core.dialogue({
                visible: false,
                lightbox: false,
                close: true,
                draggable: true
            });

            dialogue.render();
            dialogue.set('bodyContent', M.atto_mathjax.get_equation_library());
            dialogue.set('headerContent', M.util.get_string('equationlibrary', 'atto_mathjax'));
            M.atto_mathjax.librarydialogue = dialogue;
        } else {
            dialogue = M.atto_mathjax.librarydialogue;
        }

        dialogue.show();
        MathJax.Hub.Queue(["Typeset",MathJax.Hub,"atto_mathjax_equationlibrary"]);
    },

    /**
     * Get the HTML for the equation library.
     *
     * @return string
     */
    get_equation_library : function() {
        var buttons = '', i = 0, showsource = M.util.get_string('showsource', 'atto_mathjax');

        for (i = 0; i < this.equationlibrary.length; i++) {
            equation = this.equationlibrary[i];
            buttons += '<button title="' + showsource + '" data-source="' + equation + '">$$' + equation + '$$</button>';
        }

        var content = Y.Node.create('<form class="atto_form" id="atto_mathjax_equationlibrary">' +
                                    buttons +
                                    '<label for="atto_mathjax_source">' +
                                    M.util.get_string('equationsource', 'atto_mathjax') +
                                    '</label>' +
                                    '<input type="text" readonly="true" ' +
                                    '    id="atto_mathjax_source" class="fullwidth"/>' +
                                    '</form>');
        content.delegate('click', M.atto_mathjax.update_source, 'button', this);
        return content;
    },

    /**
     * Update the source preview field and select all the text in it.
     *
     * @return string
     */
    update_source : function(e) {
        var buttonnode, sourcetext, sourcenode;
        e.preventDefault();
        buttonnode = e.target.ancestor('button');
        sourcetext = buttonnode.getAttribute('data-source');
        if (sourcetext) {
            sourcenode = e.target.ancestor('form').one('input');
            sourcenode.set('value', sourcetext);
            sourcenode.select();
        }
    },

    /**
     * Return the HTML of the form to show in the dialogue.
     *
     * @method get_form_content
     * @param string elementid
     * @return string
     */
    get_form_content : function(elementid) {
        var content = Y.Node.create('<form class="atto_form">' +
                             '<label for="atto_mathjax_equation">' + M.util.get_string('editequation', 'atto_mathjax') +
                             '</label>' +
                             '<textarea class="fullwidth" id="atto_mathjax_equation" rows="8"></textarea><br/>' +
                             '<p>' + M.util.get_string('equationhelp', 'atto_mathjax') + '</p>' +
                             '<label for="atto_mathjax_preview">' + M.util.get_string('preview', 'atto_mathjax') +
                             ' ( <a href="#"> Update </a> )' +
                             '</label>' +
                             '<div class="fullwidth" id="atto_mathjax_preview" aria-live="polite">$$ $$</div>' +
                             '<p><a href="#" id="atto_mathjax_viewlibrary">' +
                             M.util.get_string('viewequationlibrary', 'atto_mathjax') + '</a></p>' +
                             '<div class="mdl-align">' +
                             '<br/>' +
                             '<button id="atto_mathjax_submit">' +
                             M.util.get_string('insertequation', 'atto_mathjax') +
                             '</button>' +
                             '</div>' +
                             '</form>');

        content.one('#atto_mathjax_submit').on('click', M.atto_mathjax.set_equation, this, elementid);
        content.one('#atto_mathjax_equation').on('change', M.atto_mathjax.update_preview, this, elementid);
        content.one('#atto_mathjax_viewlibrary').on('click', M.atto_mathjax.view_library, this, elementid);
        return content;
    }
};
