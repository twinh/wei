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

  doc.search('a').each do |link|
    if link['href'].start_with? "http"
      link['target'] = '_blank'
    elsif link['href'].start_with? "../"
      link['target'] = '_blank'
      link['href'] = 'https://github.com/twinh/widget/tree/master/docs/zh-CN/api/' + link['href']
    else
      url = "#" + link['href']
      link['href'] = url[0..-4]
    end
  end

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
    'isBetween', 'isLength', 'isCharLength', 'isMax', 'isMaxLength', 'isMin', 'isMinLength',
    'isDate', 'isDateTime', 'isTime',
    'isDir', 'isExists', 'isFile', 'isImage',
    'isEmail', 'isIp', 'isTld', 'isUrl', 'isUuid',
    'isCreditCard',
    'isChinese', 'isIdCardCn', 'isIdCardHk', 'isIdCardMo', 'isIdCardTw', 'isPhoneCn', 'isPostcodeCn', 'isQQ', 'isMobileCn',
    'isAllof', 'isNoneof', 'isOneof', 'isSomeof',
    'isRecordExists',
    'isAll', 'isCallback', 'isColor',
  # request
    'request', 'cookie', 'session', 'ua', 'upload',
  # response
    'response', 'download', 'flush', 'json', 'redirect',
  # view
    'escape', 'view',
  # others
    'arr', 'config', 'env', 'error', 'gravatar', 'logger', 'pinyin', 'uuid',
  # third party
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
