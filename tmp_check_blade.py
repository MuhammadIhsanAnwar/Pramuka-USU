from html.parser import HTMLParser
import re

path = 'd:\\0_Project_VS_Code\\Pramuka-USU\\resources\\views\\public\\home.blade.php'
text = open(path, encoding='utf-8').read()
# remove blade directives and php code to simplify tag checking
text = re.sub(r'@\w+(?:\([^)]*\))?', '', text)
text = re.sub(r'\{\{[^}]*\}\}', '', text)
text = re.sub(r'\{\%[^%]*\%\}', '', text)

class TagChecker(HTMLParser):
    def __init__(self):
        super().__init__()
        self.stack = []
        self.errors = []
    def handle_starttag(self, tag, attrs):
        if tag in ('img', 'input', 'br', 'hr', 'meta', 'link', 'area', 'base', 'col', 'embed', 'source', 'track', 'wbr'): return
        self.stack.append(tag)
    def handle_endtag(self, tag):
        if not self.stack:
            self.errors.append(f'Extra closing </{tag}>')
            return
        if self.stack[-1] == tag:
            self.stack.pop()
        else:
            self.errors.append(f'Expected </{self.stack[-1]}> but got </{tag}>')

checker = TagChecker()
checker.feed(text)
print('errors:', checker.errors)
print('stack:', checker.stack)
