<?php
/*
Plugin Name: Menu Darkmode Super Pro
Plugin URI: https://example.com
Description: 导航菜单添加超炫 SVG 动态夜间/日间/自动切换，记忆用户选择。
Version: 1.0
Author: 码铃薯
Author URI: https://tudoucode.cn
*/

if ( ! defined( 'ABSPATH' ) ) exit;

// 在菜单末尾添加按钮
add_filter('wp_nav_menu_items', 'mdsuper_add_darkmode_button', 10, 2);
function mdsuper_add_darkmode_button($items, $args) {
    $button = '<li class="menu-item menu-item-darkmode-toggle">
    <button id="darkmode-toggle" aria-label="切换模式" style="
        background:none;
        border:none;
        cursor:pointer;
        padding:0 10px;
        width:32px;
        height:32px;
    ">
    <svg id="darkmode-icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <circle cx="12" cy="12" r="5"></circle>
      <g id="rays">
        <line x1="12" y1="1" x2="12" y2="3"></line>
        <line x1="12" y1="21" x2="12" y2="23"></line>
        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
        <line x1="1" y1="12" x2="3" y2="12"></line>
        <line x1="21" y1="12" x2="23" y2="12"></line>
        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
      </g>
    </svg>
    </button>
    </li>';
    return $items . $button;
}

// 输出 CSS & JS
add_action('wp_footer', 'mdsuper_darkmode_script');
function mdsuper_darkmode_script(){
?>
<style>
body.light-mode {
    background-color: #fff222;
    color: #000;
}
body.light-mode .site-content {
    background-color: #ffffff;
}
body.dark-mode {
    background-color: #000;
    color: #ddd;
}
body.dark-mode .site-content {
    background-color: #1a1a1a;
}
body.auto-mode {}

/* SVG 动画：用 opacity 显示/隐藏射线 */
#darkmode-icon circle {
    transition: all 0.4s ease;
}
#darkmode-icon #rays {
    transition: opacity 0.4s ease;
}

.mode-light #darkmode-icon #rays {
    opacity: 1;
    stroke: #FFA500;
}
.mode-dark #darkmode-icon #rays {
    opacity: 0;
    stroke: #FFD700;
}
.mode-auto #darkmode-icon #rays {
    opacity: 0.6;
    stroke: #999;
}
.mode-light #darkmode-icon circle {
    stroke: #FFA500;
    fill: none;
}
.mode-dark #darkmode-icon circle {
    stroke: #FFD700;
    fill: #FFD700;
}
.mode-auto #darkmode-icon circle {
    stroke: #999;
    fill: #999;
}
</style>
<script>
(function(){
    let mode = localStorage.getItem('site_darkmode') || 'auto';
    applyMode(mode);

    const btn = document.getElementById('darkmode-toggle');
    if(btn){
        btn.addEventListener('click', function(){
            mode = nextMode(mode);
            localStorage.setItem('site_darkmode', mode);
            applyMode(mode);
        });
    }

    function nextMode(current){
        if(current==='light') return 'dark';
        if(current==='dark') return 'auto';
        return 'light';
    }

    function applyMode(mode){
        document.body.classList.remove('light-mode','dark-mode','auto-mode','mode-light','mode-dark','mode-auto');
        document.body.classList.add(mode+'-mode'); // 功能类
        document.body.classList.add('mode-'+mode); // 图标动画类

        if(mode==='auto'){
            let prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.body.classList.add(prefersDark ? 'dark-mode' : 'light-mode');
        }
    }

    if(mode==='auto'){
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e=>{
            applyMode('auto');
        });
    }
})();
</script>
<?php
}
