var BBCode = {
bold: function(id) {ETConversation.wrapText($("#"+id+" textarea"), "[b]", "[/b]");},
italic: function(id) {ETConversation.wrapText($("#"+id+" textarea"), "[i]", "[/i]");},
strikethrough: function(id) {ETConversation.wrapText($("#"+id+" textarea"), "[s]", "[/s]");},
header: function(id) {ETConversation.wrapText($("#"+id+" textarea"), "[h]", "[/h]");},
link: function(id) {ETConversation.wrapText($("#"+id+" textarea"), "[url=http://example.com]", "[/url]", "http://example.com", "Link Text");},
image: function(id) {ETConversation.wrapText($("#"+id+" textarea"), "[img]", "[/img]", "", "http://example.com/image.jpg");},
fixed: function(id) {ETConversation.wrapText($("#"+id+" textarea"), "[code]", "[/code]");},
textcolor: function(id) {ETConversation.wrapText($("#"+id+" textarea"), "[color=darkred]", "[/color]", "darkred", "Color Text");},
};