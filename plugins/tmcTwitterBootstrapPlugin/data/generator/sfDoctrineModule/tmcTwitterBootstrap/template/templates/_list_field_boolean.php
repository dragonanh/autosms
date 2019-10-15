[?php use_javascript('adminToggleBoolean.js'); ?]
[?php if ($value): ?]
  <a class="icon-ok _tick " href="javascript:void(0);" vt_href="[?php echo url_for($this->getModuleName().'/toggleBoolean') ?]"></a>
[?php else: ?]
  <a class="icon-remove _cross " href="javascript:void(0);" vt_href="[?php echo url_for($this->getModuleName().'/toggleBoolean') ?]"></a>
[?php endif; ?]