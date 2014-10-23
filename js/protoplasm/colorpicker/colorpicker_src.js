if (typeof Control == 'undefined') Control = {};

/**
 * class Control.ColorPicker
 * 
 * Transforms an ordinary input textbox into an interactive color chooser,
 * allowing the user to select a color from a swatch palette.
 *
 * Control ID: `colorpicker`
 *
 * Features:
 *
 * * Allows saving custom colors to the palette for later use
 * * Customizable by CSS
**/
Control.ColorPicker = Class.create({

/**
 * new Control.ColorPicker(element[, options])
 * - element (String | Element): A `<input type="text">` element (or DOM ID).
 * - options (Hash): Additional options for the control.
 *
 * Create a new color picker from the given `<input type="text">`
 * element.
 *
 * Additional options:
 *
 * * className: The CSS class to apply to the dialog panel.
 * * colors: An array of 40 colors to display in the picker.
 * * addLabel: The label for the "Add" button (for internationalization).
**/
	initialize: function (element, options) {

/**
 * Control.ColorPicker#element -> Element
 *
 * The underlying `<input>` element passed to the constructor.
**/
		this.element = $(element);

		if (cp = this.element.retrieve('colorpicker'))
			cp.destroy();

		this.options = Object.extend({
				className: 'colorpickerControl'
			}, options || {});

		this.colorpicker = new Control.ColorPicker.Panel({
				onSelect: this.colorSelected.bind(this)
			});

		if (typeof Protoplasm != 'undefined')
			Protoplasm.loadStylesheet('colorpicker.css', 'colorpicker');

		this.open = false;
		this.dialog = new Element('div', {'style': 'position:absolute;'});
		var cpCont = new Element('div', { 'class': this.options.className });
		cpCont.appendChild(this.colorpicker.element);
		this.dialog.appendChild(cpCont);

		// Wrap element in a relative div to overlay the clickable swatch
		this.wrapper = this.element.wrap(new Element('div'));
		this.wrapper.style.position = 'relative';

		// Get layout information for the swatch position
		var layout = this.element.getLayout();
		var size = layout.get('height') - 2;
		var topPad = layout.get('padding-top');
		if (topPad < 1) {
			topPad = 1;
			size -= (topPad - layout.get('padding-top')) * 2;
		}
		var rightPad = layout.get('padding-right');
		if (rightPad < 1) rightPad = 1;

		// Create the color swatch
		this.swatch = new Element('div', {
			'class': 'inputExtension',
			'title': 'Open color palette' });
		this.swatch.setStyle({'border': '1px solid gray',
			'position': 'absolute',
			'fontSize': '1px',
			'width': size + 'px',
			'height': size + 'px',
			'backgroundColor': this.element.value });
		this.wrapper.appendChild(this.swatch);

		// Set the swatch position
		this.swatch.clonePosition(this.element, {
			'setWidth': false,
			'setHeight': false,
			'offsetTop': topPad + layout.get('border-top'),
			'offsetLeft': layout.get('padding-box-width') + 1 + layout.get('border-left') });
		this.oldPadding = this.element.style.paddingRight;
		this.element.style.paddingRight = (size + 3 + layout.get('padding-left') + rightPad) + 'px';
		this.element.maxLength = 7;

		this.listeners = [
			this.element.on('change', this.textChanged.bindAsEventListener(this)),
			this.element.on('blur',  this.hide.bindAsEventListener(this)),
			this.swatch.on('click', this.toggle.bindAsEventListener(this)),
			this.swatch.on('selectstart', Event.stop)
		];
		this.clickListener = null;

		this.element.store('colorpicker', this);
		this.destructor = Event.on(window, 'unload', this.destroy.bind(this));
	},

/**
 * Control.ColorPicker#destroy() -> null
 *
 * Destroy this control and return the underlying element to
 * its original behavior.
**/
	destroy: function() {
		for (var i = 0; i < this.listeners.length; i++)
			this.listeners[i].stop();
		if (this.clickListener)
			this.clickListener.stop();
		this.wrapper.parentNode.replaceChild(this.element, this.wrapper);
		this.element.style.paddingRight = this.oldPadding;
		this.element.store('colorpicker', null);
		this.destructor.stop();
	},

	colorSelected: function(color) {
		this.element.value = color;
		this.swatch.style.backgroundColor = color;
		this.hide();
	},

	textChanged: function(e) {
		this.swatch.style.backgroundColor = this.element.value;
	},

/**
 * Control.ColorPicker#toggle() -> null
 *
 * Toggle the visibility of the picker panel for this control.
**/
	toggle: function(e) {
		if (this.open) this.hide();
		else this.show();
	},

/**
 * Control.ColorPicker#show() -> null
 *
 * Show the picker panel for this control.
**/
	show: function(e) {
		if (!this.open) {
			var layout = this.element.getLayout();
			this.swatch.parentNode.appendChild(this.dialog);
			var offsetTop = layout.get('border-box-height') - layout.get('border-bottom');
			this.dialog.clonePosition(this.element, {
				'setWidth': false,
				'setHeight': false,
				'offsetTop': offsetTop});
			this.clickListener = document.on('click',
				this.clickHandler.bindAsEventListener(this));
			this.open = true;
		}
	},

/**
 * Control.ColorPicker#hide() -> null
 *
 * Hide the picker panel for this control.
**/
	hide: function(e) {
		if (this.open) {
			if (this.clickListener)
				this.clickListener.stop();
			this.dialog.remove();
			this.open = false;
		}
	},

	clickHandler: function(e) {
		var element = Event.element(e);
		do {
			if (element == this.swatch || element == this.dialog)
				return;
		} while (element = element.parentNode);
		this.hide();
	}
});

/**
 * class Control.ColorPicker.Panel
 *
 * The dialog panel that is displayed when the color picker is opened.
**/
Control.ColorPicker.Panel = Class.create({

/**
 * new Control.ColorPicker.Panel([options])
 * - options (Hash): Additional options for the panel.
 *
 * Create a new color picker panel.
 *
 * Additional options:
 *
 * * colors: An array of 40 colors to display in the picker.
 * * addLabel: The label for the "Add" button (for internationalization).
**/
	initialize: function(options) {
		this.options = Object.extend({
				addLabel: 'Add',
				colors: Array(
					'#000000', '#993300', '#333300', '#003300', '#003366', '#000080', '#333399', '#333333',
					'#800000', '#FF6600', '#808000', '#008000', '#008080', '#0000FF', '#666699', '#808080',
					'#FF0000', '#FF9900', '#99CC00', '#339966', '#33CCCC', '#3366FF', '#800080', '#969696',
					'#FF00FF', '#FFCC00', '#FFFF00', '#00FF00', '#00FFFF', '#00CCFF', '#993366', '#C0C0C0',
					'#FF99CC', '#FFCC99', '#FFFF99', '#CCFFCC', '#CCFFFF', '#99CCFF', '#CC99FF', '#FFFFFF'),
				onSelect: Prototype.emptyFunction
			}, options || {});
		this.customSwatches = [];
		this.activeCustom =  null,

/**
 * Control.ColorPicker.Panel#element -> Element
 *
 * The root Element of this dialog panel.
**/
		this.element = this.create();
	},

	create: function() {
		var cont = document.createElement('div');
		var colors = this.options.colors;
		var row, cell;

		// Create swatch table
		var table = new Element('table', {'cellPadding': 0, 'cellSpacing': 0, 'border': 0});
		for (var i = 0; i < 5; ++i) {
			row = table.insertRow(i);
			for (var j = 0; j < 8; ++j) {
				cell = row.insertCell(j);
				Element.setStyle(cell, { 'border': '0px', 'padding': '0px' });
				var color = colors[(8 * i) + j];
				var swatch = new Element('div', {'style': 'width:15px;height:15px;font-size:1px;border:1px solid #EEEEEE;background-color:'+color+';padding:0'});
				swatch.on('click', this.clickListener(color));
				swatch.on('mouseover', this.hoverListener(color));
				cell.appendChild(swatch);
			}
		}

		this.addSpacerRow(table, 5);

		// Add custom color row
		row = table.insertRow(6);
		var customColors = this.loadSetting('customColors')
			?  this.loadSetting('customColors').split(',')
			: new Array();
		this.customSwatches = [];
		for (var i = 0; i < 8; ++i) {
			cell = row.insertCell(i);
			Element.setStyle(cell, { 'border': '0', 'padding': '0' });
			var color = customColors[i] ? customColors[i] : '#000000';
			var swatch = new Element('div', {'style': 'width:15px;height:15px;fontSize:15px;border:1px solid #EEEEEE;background-color:'+color+';padding:0'});
			cell.appendChild(swatch);
			swatch.on('click', this.customClickListener(color, swatch));
			swatch.on('mouseover', this.hoverListener(color));
			this.customSwatches.push(swatch);
		}

		this.addSpacerRow(table, 7);

		// Add custom color entry interface
		row = table.insertRow(8);
		cell = row.insertCell(0);
		Element.setStyle(cell, { 'border': '0', 'padding': '0'});
		cell.colSpan = 8;
		var entryTable = new Element('table', {'cellspacing': 0, 'cellpadding': 0, 'border': 0, 'style': 'width:136px;'});
		cell.appendChild(entryTable);

		row = entryTable.insertRow(0);
		cell = row.insertCell(0);
		Element.setStyle(cell, { 'border': '0', 'padding': '0', 'vertical-align': 'middle'});
		var preview = new Element('div', {'style': 'width:15px;height:15px;fontSize:15px;border:1px solid #EEEEEE;background-color:#000000'});
		cell.appendChild(preview);
		this.previewSwatch = preview;

		cell = row.insertCell(1);
		Element.setStyle(cell, {'border': '0', 'padding': '0', 'vertical-align': 'middle', 'text-align': 'center'});
		var textbox = new Element('input', {'type': 'text', 'value': '#000000', 'style': 'width:70px;border:1px solid gray' });
		textbox.on('keyup', function(e) {
				this.previewSwatch.style.backgroundColor = textbox.value;
			}.bindAsEventListener(this));
		cell.appendChild(textbox);
		this.customInput = textbox;

		cell = row.insertCell(2);
		Element.setStyle(cell, { 'border': '0', 'padding': '0', 'vertical-align': 'middle', 'text-align': 'right'});
		var submit = new Element('input', {'type': 'button', 'value': this.options.addLabel,
			'style': 'width:40px;border:1px solid gray'});
		submit.on('click', function(e) {
				var idx = 0;
				if (this.activeCustom) {
					for (var i = 0; i < this.customSwatches.length; ++i)
						if (this.customSwatches[i] == this.activeCustom) {
							idx = i;
							break;
						}
					this.activeCustom.style.border = '1px solid #EEEEEE';
					this.activeCustom = null;
				} else {
					var lastIndex = this.loadSetting('customColorIndex');
					if (lastIndex) idx = (parseInt(lastIndex) + 1) % 8;
				}
				this.saveSetting('customColorIndex', idx);
				customColors[idx] = this.customSwatches[idx].style.backgroundColor = this.customInput.value;
				this.customSwatches[idx].onclick = this.customClickListener(customColors[idx], this.customSwatches[idx]);
				this.customSwatches[idx].onmouseover = this.hoverListener(customColors[idx]);
				this.saveSetting('customColors', customColors.join(','));
			}.bindAsEventListener(this));
		cell.appendChild(submit);

		// Create form
		var form = new Element('form', {'style': 'margin:0;padding:0'});
		form.onsubmit = function() {
			if (this.activeCustom) this.activeCustom.style.border = '1px solid #EEEEEE';
			this.activeCustom = null;
			this.editor.setDialogColor(this.customInput.value);
			return false;
		}.bindAsEventListener(this);
		form.appendChild(table);

		// Add to dialog window
		cont.appendChild(form);
		return cont;
	},

	addSpacerRow: function(table, idx) {
		var row = table.insertRow(idx);
		cell = row.insertCell(0);
		cell.colSpan = 8;
		Element.setStyle(cell, {'border': '0', 'padding': '0'});
		cell.appendChild(new Element('hr', {'style': 'color:gray;background-color:gray;'
			+'height:1px;border:0;margin-top:3px;margin-bottom:3px;padding:0'}));
		return row;
	},

	clickListener: function(color) {
		return function(e) {
				if (this.activeCustom) this.activeCustom.style.border = '1px solid #EEEEEE';
				this.activeCustom = null;
				this.options.onSelect(color);
			}.bindAsEventListener(this);
	},

	customClickListener: function(color, element) {
		return function(e) {
			if (e.ctrlKey) {
				if (this.activeCustom)
					this.activeCustom.style.border = '1px solid #EEEEEE';
				this.activeCustom = element;
				this.activeCustom.style.border = '1px solid #FF0000';
			} else {
				this.activeCustom = null;
				this.options.onSelect(color);
			}
		}.bindAsEventListener(this);
	},

	hoverListener: function(color) {
		return function(e) {
				this.previewSwatch.style.backgroundColor = color;
				this.customInput.value = color;
			}.bindAsEventListener(this);
	},

	loadSetting: function(name) {
		name = 'colorpicker_' + name;
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for(var i=0;i < ca.length;i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1,c.length);
			if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
		}
		return null;
	},

	saveSetting: function(name, value, days) {
		name = 'colorpicker_' + name;
		if (!days) days = 180;
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
		document.cookie = name+"="+value+expires+"; path=/";
	},

	clearSetting: function(name) {
		this.saveSetting(name, "", -1);
	}

});

if (typeof Protoplasm != 'undefined')
	Protoplasm.register('colorpicker', Control.ColorPicker);
