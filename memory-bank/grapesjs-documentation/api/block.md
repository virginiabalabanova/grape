<!-- Generated by documentation.js. Update this documentation by updating the source code. -->

## Block

### Properties

*   `label` **[String][1]** Block label, eg. `My block`
*   `content` **([String][1] | [Object][2])** The content of the block. Might be an HTML string or a [Component Definition][3]
*   `media` **[String][1]?** HTML string for the media/icon of the block, eg. `<svg ...`, `<img ...`, etc.
*   `category` **[String][1]?** Block category, eg. `Basic blocks`
*   `activate` **[Boolean][4]?** If true, triggers the `active` event on the dropped component.
*   `select` **[Boolean][4]?** If true, the dropped component will be selected.
*   `resetId` **[Boolean][4]?** If true, all IDs of dropped components and their styles will be changed.
*   `disable` **[Boolean][4]?** Disable the block from being interacted
*   `onClick` **[Function][5]?** Custom behavior on click, eg. `(block, editor) => editor.getWrapper().append(block.get('content'))`
*   `attributes` **[Object][2]?** Block attributes to apply in the view element

## getId

Get block id

Returns **[String][1]**&#x20;

## getLabel

Get block label

Returns **[String][1]**&#x20;

## getMedia

Get block media

Returns **[String][1]**&#x20;

## getContent

Get block content

Returns **([Object][2] | [String][1] | [Array][6]<([Object][2] | [String][1])>)**&#x20;

## getDragDef

Get block component dragDef

Returns **ComponentDefinition**&#x20;

## getCategoryLabel

Get block category label

Returns **[String][1]**&#x20;

[1]: https://developer.mozilla.org/docs/Web/JavaScript/Reference/Global_Objects/String

[2]: https://developer.mozilla.org/docs/Web/JavaScript/Reference/Global_Objects/Object

[3]: /modules/Components.html#component-definition

[4]: https://developer.mozilla.org/docs/Web/JavaScript/Reference/Global_Objects/Boolean

[5]: https://developer.mozilla.org/docs/Web/JavaScript/Reference/Statements/function

[6]: https://developer.mozilla.org/docs/Web/JavaScript/Reference/Global_Objects/Array
