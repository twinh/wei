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
      link['href'] = 'https://github.com/twinh/wei/tree/master/docs/zh-CN/api/' + link['href']
    else
      url = "#" + link['href'].split('#')[0]
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

weis = [
  # service manager
    'wei',
  # cache
    'cache', 'apc', 'arrayCache', 'bicache', 'couchbase', 'dbCache', 'fileCache', 'memcache', 'memcached', 'mongoCache', 'redis',
  # database
    'db',
    'book/active-record-basic', 'book/active-record-query-builder', 'book/active-record-callbacks', 'book/active-record-status',
    'call',
  # validation
    'validate',
    #
    'isAlnum', 'isAlpha', 'isBlank', 'isDecimal', 'isDigit', 'isDivisibleby', 'isDoubleByte', 'isEndsWith', 'isIn', 'isLowercase', 'isLuhn', 'isNaturalNumber', 'isNull', 'isNumber', 'isPositiveInteger', 'isPresent', 'isRegex', 'isRequired', 'isStartsWith', 'isType', 'isUppercase',
    # Length
    'isLength', 'isCharLength', 'isMaxLength', 'isMinLength',
    # Comparison
    'isEqualTo', 'isIdenticalTo', 'isGreaterThan', 'isGreaterThanOrEqual', 'isLessThan', 'isLessThanOrEqual', 'isBetween',
    # Date & Time
    'isDate', 'isDateTime', 'isTime',
    # File & Directory
    'isDir', 'isExists', 'isFile', 'isImage',
    'isEmail', 'isIp', 'isTld', 'isUrl', 'isUuid',
    'isCreditCard', 'isPhone',
    'isChinese', 'isIdCardCn', 'isIdCardHk', 'isIdCardMo', 'isIdCardTw', 'isPhoneCn', 'isPlateNumberCn', 'isPostcodeCn', 'isQQ', 'isMobileCn',
    'isAllof', 'isNoneof', 'isOneof', 'isSomeof',
    'isRecordExists',
    'isAll', 'isCallback', 'isColor', 'isPassword',
  # request
    'request', 'cookie', 'session', 'ua', 'upload',
  # response
    'response',
  # view
    'asset', 'e', 'view',
  # app
    'app',
  # others
    'config', 'env', 'error', 'gravatar', 'lock', 'logger', 'password', 'pinyin', 'uuid',
  # third party
    'phpError'
]

sections = {}

weis.each do |name|
  if name.index("/")
    id = name.gsub("/", "-")
  else
    id = name
    name = "api/" + name
  end
  content = File.read("../wei/docs/zh-CN/#{name}.md")
  content = markdown(content)
  sections[name] = {
    "id" => id,
    "content" => content
  }
end

rhtml = ERB.new(File.read('index.tmpl.html'))

content = rhtml.result()

File.write('index.html', content)
