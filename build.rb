require 'redcarpet/compat'
require 'pygments'
require 'nokogiri'
require 'erb'

Dir.chdir File.dirname(__FILE__)

def syntax_highlighter(html, lexer = 'php')
  doc = Nokogiri::HTML.fragment(html)

  doc.search('table').each do |table|
    table['class'] = "table table-bordered table-striped"
  end

  doc.search('h1').wrap('<div class="page-header"></div>')

  doc.search('pre').each do |pre|
    code = Pygments.highlight(pre.text.rstrip, :lexer => lexer, :options => {:startinline => true})
    code = code.to_s.gsub('<pre>', '<pre><code class="' + lexer + '">').gsub('</pre>', '</code></pre>')
    pre.replace code
  end
  doc.to_s
end

def markdown(text)
  options = [:filter_html, :autolink, :no_intraemphasis, :fenced_code, :gh_blockcode, :tables]
  syntax_highlighter(Markdown.new(text, *options).to_html)
end

widgets = [
  # widget manager
    'widget',
  # cache
    'cache', 'apc', 'arrayCache', 'bicache', 'couchbase', 'dbCache', 'fileCache', 'memcache', 'memcached', 'mongoCache', 'redis',
  # database
    'db', 'queryBuilder',
    'call',
  # validation
    'validate',  'is',
    'isAlnum', 'isAlpha', 'isBlank', 'isDecimal', 'isDigit', 'isDivisibleby', 'isDoubleByte', 'isEmpty', 'isEndsWith', 'isEquals', 'isIn', 'isLowercase', 'isNull', 'isNumber', 'isRegex', 'isRequired', 'isStartsWith', 'isType', 'isUppercase',
    'isBetween', 'isLength', 'isMax', 'isMaxLength', 'isMin', 'isMinLength',
    'isDate', 'isDateTime', 'isTime',
    'isDir', 'isExists', 'isFile', 'isImage',
    'isEmail', 'isIp', 'isTld', 'isUrl', 'isUuid',
    'isCreditCard',
    'isChinese', 'isIdCardCn', 'isIdCardHk', 'isIdCardMo', 'isIdCardTw', 'isPhoneCn', 'isPostcodeCn', 'isQQ', 'isMobileCn',
    'isAllof', 'isNoneof', 'isOneof', 'isSomeof',
    'isRecordExists',
    'isAll', 'isCallback', 'isColor',
  # request-section
    'request', 'cookie', 'session', 'ua', 'upload',
  # response-section
    'response', 'download', 'flush', 'json', 'redirect',
  # view-section
    'escape', 'view',
  # others
    'arr', 'config', 'env', 'error', 'event', 'gravatar', 'logger', 'map', 'pinyin', 'uuid',
  # third party
    # view
    'smarty',
    # others
    'monolog', 
    'phpError'
]

sections = {}

widgets.each do |name|
  if name.index("/")
    id = name.gsub("/", "-")
  else
    id = name
    name = "api/" + name
  end
  content = File.read("../widget/docs/zh-CN/#{name}.md")
  content = markdown(content)
  sections[name] = {
    "id" => id,
    "content" => content
  }
end

rhtml = ERB.new(File.read('index.tmpl.html'))

content = rhtml.result()

File.write('index.html', content)
