<!-- Generated by documentation.js. Update this documentation by updating the source code. -->

## Editor

Editor contains the top level API which you'll probably use to customize the editor or extend it with plugins.
You get the Editor instance on init method and you can pass options via its [Configuration Object][1]

```js
const editor = grapesjs.init({
   // options
});
```

## Available Events
* `update` Event triggered on any change of the project (eg. component added/removed, style changes, etc.)

```javascript
editor.on('update', () => { ... });
```

* `undo` Undo executed.

```javascript
editor.on('undo', () => { ... });
```

* `redo` Redo executed.

```javascript
editor.on('redo', () => { ... });
```

* `load` Editor is loaded. At this stage, the project is loaded in the editor and elements in the canvas are rendered.

```javascript
editor.on('load', () => { ... });
```

* `project:load` Project JSON loaded in the editor. The event is triggered on the initial load and on the `editor.loadProjectData` method.

```javascript
editor.on('project:load', ({ project, initial }) => { ... });
```

* `project:get` Event triggered on request of the project data. This can be used to extend the project with custom data.

```javascript
editor.on('project:get', ({ project }) => { project.myCustomKey = 'value' });
```

* `log` Log message triggered.

```javascript
editor.on('log', (msg, opts) => { ... });
```

* `telemetry:init` Initial telemetry data are sent.

```javascript
editor.on('telemetry:init', () => { ... });
```

* `destroy` Editor started destroy (on `editor.destroy()`).

```javascript
editor.on('destroy', () => { ... });
```

* `destroyed` Editor destroyed.

```javascript
editor.on('destroyed', () => { ... });
```

### Components

Check the [Components][2] module.

### Keymaps

Check the [Keymaps][3] module.

### Style Manager

Check the [Style Manager][4] module.

### Storage

Check the [Storage][5] module.

### Canvas

Check the [Canvas][6] module.

### RTE

Check the [Rich Text Editor][7] module.

### Commands

Check the [Commands][8] module.

### Selectors

Check the [Selectors][9] module.

### Blocks

Check the [Blocks][10] module.

### Assets

Check the [Assets][11] module.

### Modal

Check the [Modal][12] module.

### Devices

Check the [Devices][13] module.

### Parser

Check the [Parser][14] module.

### Pages

Check the [Pages][15] module.

## Methods

## getConfig

Returns configuration object

### Parameters

*   `prop` **P?**&#x20;

Returns **any** Returns the configuration object or the value of the specified property

## getHtml

Returns HTML built inside canvas

### Parameters

*   `opts` **[Object][16]** Options (optional, default `{}`)

    *   `opts.component` **Component?** Return the HTML of a specific Component
    *   `opts.cleanId` **[Boolean][17]** Remove unnecessary IDs (eg. those created automatically) (optional, default `false`)

Returns **[string][18]** HTML string

## getCss

Returns CSS built inside canvas

### Parameters

*   `opts` **[Object][16]** Options (optional, default `{}`)

    *   `opts.component` **Component?** Return the CSS of a specific Component
    *   `opts.json` **[Boolean][17]** Return an array of CssRules instead of the CSS string (optional, default `false`)
    *   `opts.avoidProtected` **[Boolean][17]** Don't include protected CSS (optional, default `false`)
    *   `opts.onlyMatched` **[Boolean][17]** Return only rules matched by the passed component. (optional, default `false`)
    *   `opts.keepUnusedStyles` **[Boolean][17]** Force keep all defined rules. Toggle on in case output looks different inside/outside of the editor. (optional, default `false`)

Returns **([String][18] | [Array][19]\<CssRule>)** CSS string or array of CssRules

## getJs

Returns JS of all components

### Parameters

*   `opts` **[Object][16]** Options (optional, default `{}`)

    *   `opts.component` **Component?** Get the JS of a specific component

Returns **[String][18]** JS string

## getComponents

Return the complete tree of components. Use `getWrapper` to include also the wrapper

Returns **Components**&#x20;

## getWrapper

Return the wrapper and its all components

Returns **Component**&#x20;

## setComponents

Set components inside editor's canvas. This method overrides actual components

### Parameters

*   `components` **([Array][19]<[Object][16]> | [Object][16] | [string][18])** HTML string or components model
*   `opt` **[Object][16]** the options object to be used by the \[setComponents][em#setComponents][20] method (optional, default `{}`)

### Examples

```javascript
editor.setComponents('<div class="cls">New component</div>');
// or
editor.setComponents({
 type: 'text',
  classes:['cls'],
  content: 'New component'
});
```

Returns **this**&#x20;

## addComponents

Add components

### Parameters

*   `components` **([Array][19]<[Object][16]> | [Object][16] | [string][18])** HTML string or components model
*   `opts` **[Object][16]** Options

    *   `opts.avoidUpdateStyle` **[Boolean][17]** If the HTML string contains styles,
        by default, they will be created and, if already exist, updated. When this option
        is true, styles already created will not be updated. (optional, default `false`)

### Examples

```javascript
editor.addComponents('<div class="cls">New component</div>');
// or
editor.addComponents({
 type: 'text',
  classes:['cls'],
  content: 'New component'
});
```

Returns **[Array][19]\<Component>**&#x20;

## getStyle

Returns style in JSON format object

Returns **[Object][16]**&#x20;

## setStyle

Set style inside editor's canvas. This method overrides actual style

### Parameters

*   `style` **([Array][19]<[Object][16]> | [Object][16] | [string][18])** CSS string or style model
*   `opt` **any**  (optional, default `{}`)

### Examples

```javascript
editor.setStyle('.cls{color: red}');
//or
editor.setStyle({
  selectors: ['cls'],
  style: { color: 'red' }
});
```

Returns **this**&#x20;

## addStyle

Add styles to the editor

### Parameters

*   `style` **([Array][19]<[Object][16]> | [Object][16] | [string][18])** CSS string or style model
*   `opts`   (optional, default `{}`)

### Examples

```javascript
editor.addStyle('.cls{color: red}');
```

Returns **[Array][19]\<CssRule>** Array of created CssRule instances

## getSelected

Returns the last selected component, if there is one

Returns **Model**&#x20;

## getSelectedAll

Returns an array of all selected components

Returns **[Array][19]**&#x20;

## getSelectedToStyle

Get a stylable entity from the selected component.
If you select a component without classes the entity is the Component
itself and all changes will go inside its 'style' attribute. Otherwise,
if the selected component has one or more classes, the function will
return the corresponding CSS Rule

Returns **Model**&#x20;

## select

Select a component

### Parameters

*   `el` **(Component | [HTMLElement][21])** Component to select
*   `opts` **[Object][16]?** Options

    *   `opts.scroll` **[Boolean][17]?** Scroll canvas to the selected element

### Examples

```javascript
// Select dropped block
editor.on('block:drag:stop', function(model) {
 editor.select(model);
});
```

Returns **this**&#x20;

## selectAdd

Add component to selection

### Parameters

*   `el` **(Component | [HTMLElement][21] | [Array][19])** Component to select

### Examples

```javascript
editor.selectAdd(model);
```

Returns **this**&#x20;

## selectRemove

Remove component from selection

### Parameters

*   `el` **(Component | [HTMLElement][21] | [Array][19])** Component to select

### Examples

```javascript
editor.selectRemove(model);
```

Returns **this**&#x20;

## selectToggle

Toggle component selection

### Parameters

*   `el` **(Component | [HTMLElement][21] | [Array][19])** Component to select

### Examples

```javascript
editor.selectToggle(model);
```

Returns **this**&#x20;

## getEditing

Returns, if active, the Component enabled in rich text editing mode.

### Examples

```javascript
const textComp = editor.getEditing();
if (textComp) {
 console.log('HTML: ', textComp.toHTML());
}
```

Returns **(Component | null)**&#x20;

## setDevice

Set device to the editor. If the device exists it will
change the canvas to the proper width

### Parameters

*   `name` **[string][18]** Name of the device

### Examples

```javascript
editor.setDevice('Tablet');
```

Returns **this**&#x20;

## getDevice

Return the actual active device

### Examples

```javascript
var device = editor.getDevice();
console.log(device);
// 'Tablet'
```

Returns **[string][18]** Device name

## runCommand

Execute command

### Parameters

*   `id` **[string][18]** Command ID
*   `options` **[Object][16]** Custom options (optional, default `{}`)

### Examples

```javascript
editor.runCommand('myCommand', {someValue: 1});
```

Returns **any** The return is defined by the command

## stopCommand

Stop the command if stop method was provided

### Parameters

*   `id` **[string][18]** Command ID
*   `options` **[Object][16]** Custom options (optional, default `{}`)

### Examples

```javascript
editor.stopCommand('myCommand', {someValue: 1});
```

Returns **any** The return is defined by the command

## store

Store data to the current storage.
This will reset the counter of changes (`editor.getDirtyCount()`).

### Parameters

*   `options` **[Object][16]?** Storage options.

### Examples

```javascript
const storedData = await editor.store();
```

Returns **[Object][16]** Stored data.

## load

Load data from the current storage.

### Parameters

*   `options` **[Object][16]?** Storage options.
*   `loadOptions` **[Object][16]** Load options. (optional, default `{}`)

    *   `loadOptions.clear` **[Boolean][17]** Clear the editor state (eg. dirty counter, undo manager, etc.). (optional, default `false`)

### Examples

```javascript
const data = await editor.load();
```

Returns **[Object][16]** Loaded data.

## getProjectData

Get the JSON project data, which could be stored and loaded back with `editor.loadProjectData(json)`

### Examples

```javascript
console.log(editor.getProjectData());
// { pages: [...], styles: [...], ... }
```

Returns **[Object][16]**&#x20;

## loadProjectData

Load data from the JSON project

### Parameters

*   `data` **[Object][16]** Project to load

### Examples

```javascript
editor.loadProjectData({ pages: [...], styles: [...], ... })
```

## getContainer

Returns container element. The one which was indicated as 'container'
on init method

Returns **[HTMLElement][21]**&#x20;

## getDirtyCount

Return the count of changes made to the content and not yet stored.
This count resets at any `store()`

Returns **[number][22]**&#x20;

## clearDirtyCount

Reset the counter of changes.

## refresh

Update editor dimension offsets

This method could be useful when you update, for example, some position
of the editor element (eg. canvas, panels, etc.) with CSS, where without
refresh you'll get misleading position of tools

### Parameters

*   `opts` **{tools: [boolean][17]?}?**&#x20;
*   `options` **[Object][16]?** Options

    *   `options.tools` **[Boolean][17]** Update the position of tools (eg. rich text editor, component highlighter, etc.) (optional, default `false`)

## setCustomRte

Replace the built-in Rich Text Editor with a custom one.

### Parameters

*   `obj` **[Object][16]** Custom RTE Interface

### Examples

```javascript
editor.setCustomRte({
  // Function for enabling custom RTE
  // el is the HTMLElement of the double clicked Text Component
  // rte is the same instance you have returned the first time you call
  // enable(). This is useful if need to check if the RTE is already enabled so
  // ion this case you'll need to return the RTE and the end of the function
  enable: function(el, rte) {
    rte = new MyCustomRte(el, {}); // this depends on the Custom RTE API
    ...
    return rte; // return the RTE instance
  }

  // Disable the editor, called for example when you unfocus the Text Component
 disable: function(el, rte) {
    rte.blur(); // this depends on the Custom RTE API
 }

// Called when the Text Component is focused again. If you returned the RTE instance
// from the enable function, the enable won't be called again instead will call focus,
// in this case to avoid double binding of the editor
 focus: function (el, rte) {
  rte.focus(); // this depends on the Custom RTE API
 }
});
```

## setCustomParserCss

Replace the default CSS parser with a custom one.
The parser function receives a CSS string as a parameter and expects
an array of CSSRule objects as a result. If you need to remove the
custom parser, pass `null` as the argument

### Parameters

*   `parser` **([Function][23] | null)** Parser function

### Examples

```javascript
editor.setCustomParserCss(css => {
 const result = [];
 // ... parse the CSS string
 result.push({
   selectors: '.someclass, div .otherclass',
   style: { color: 'red' }
 })
 // ...
 return result;
});
```

Returns **this**&#x20;

## setDragMode

Change the global drag mode of components.
To get more about this feature read: [https://github.com/GrapesJS/grapesjs/issues/1936][24]

### Parameters

*   `value` **[String][18]** Drag mode, options: 'absolute' | 'translate'

Returns **this**&#x20;

## log

Trigger event log message

### Parameters

*   `msg` **any** Message to log
*   `opts` **[Object][16]** Custom options (optional, default `{}`)

    *   `opts.ns` **[String][18]** Namespace of the log (eg. to use in plugins) (optional, default `''`)
    *   `opts.level` **[String][18]** Level of the log, `debug`, `info`, `warning`, `error` (optional, default `'debug'`)

### Examples

```javascript
editor.log('Something done!', { ns: 'from-plugin-x', level: 'info' });
// This will trigger following events
// `log`, `log:info`, `log-from-plugin-x`, `log-from-plugin-x:info`
// Callbacks of those events will always receive the message and
// options, as arguments, eg:
// editor.on('log:info', (msg, opts) => console.info(msg, opts))
```

Returns **this**&#x20;

## t

Translate label

### Parameters

*   `args` **...[Array][19]\<any>**&#x20;
*   `key` **[String][18]** Label to translate
*   `opts` **[Object][16]?** Options for the translation

    *   `opts.params` **[Object][16]?** Params for the translation
    *   `opts.noWarn` **[Boolean][17]?** Avoid warnings in case of missing resources

### Examples

```javascript
editor.t('msg');
// use params
editor.t('msg2', { params: { test: 'hello' } });
// custom local
editor.t('msg2', { params: { test: 'hello' } l: 'it' });
```

Returns **[String][18]**&#x20;

## on

Attach event

### Parameters

*   `event` **[string][18]** Event name
*   `callback` **[Function][23]** Callback function

Returns **this**&#x20;

## once

Attach event and detach it after the first run

### Parameters

*   `event` **[string][18]** Event name
*   `callback` **[Function][23]** Callback function

Returns **this**&#x20;

## off

Detach event

### Parameters

*   `event` **[string][18]** Event name
*   `callback` **[Function][23]** Callback function

Returns **this**&#x20;

## trigger

Trigger event

### Parameters

*   `event` **[string][18]** Event to trigger
*   `args` **...[Array][19]\<any>**&#x20;

Returns **this**&#x20;

## destroy

Destroy the editor

## render

Render editor

Returns **[HTMLElement][21]**&#x20;

## onReady

Trigger a callback once the editor is loaded and rendered.
The callback will be executed immediately if the method is called on the already rendered editor.

### Parameters

*   `clb` **[Function][23]** Callback to trigger

### Examples

```javascript
editor.onReady(() => {
  // perform actions
});
```

## html

Print safe HTML by using ES6 tagged template strings.

### Parameters

*   `literals` **[Array][19]<[String][18]>**&#x20;
*   `substs` **[Array][19]<[String][18]>**&#x20;

### Examples

```javascript
const unsafeStr = '<script>....</script>';
const safeStr = '<b>Hello</b>';
// Use `$${var}` to avoid escaping
const strHtml = editor.html`Escaped ${unsafeStr} unescaped $${safeStr}`;
```

Returns **[String][18]**&#x20;

[1]: https://github.com/GrapesJS/grapesjs/blob/master/src/editor/config/config.ts

[2]: /api/components.html

[3]: /api/keymaps.html

[4]: /api/style_manager.html

[5]: /api/storage_manager.html

[6]: /api/canvas.html

[7]: /api/rich_text_editor.html

[8]: /api/commands.html

[9]: /api/selector_manager.html

[10]: /api/block_manager.html

[11]: /api/assets.html

[12]: /api/modal_dialog.html

[13]: /api/device_manager.html

[14]: /api/parser.html

[15]: /api/pages.html

[16]: https://developer.mozilla.org/docs/Web/JavaScript/Reference/Global_Objects/Object

[17]: https://developer.mozilla.org/docs/Web/JavaScript/Reference/Global_Objects/Boolean

[18]: https://developer.mozilla.org/docs/Web/JavaScript/Reference/Global_Objects/String

[19]: https://developer.mozilla.org/docs/Web/JavaScript/Reference/Global_Objects/Array

[20]: em#setComponents

[21]: https://developer.mozilla.org/docs/Web/HTML/Element

[22]: https://developer.mozilla.org/docs/Web/JavaScript/Reference/Global_Objects/Number

[23]: https://developer.mozilla.org/docs/Web/JavaScript/Reference/Statements/function

[24]: https://github.com/GrapesJS/grapesjs/issues/1936
