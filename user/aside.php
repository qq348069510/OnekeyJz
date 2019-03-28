<?php
/**
 * Copyright (C) <2018>  辽宁微时光科技有限公司
 * Author 酸奶(qq348069510)
 * Email admin@vtimecn.com or 348069510@qq.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if(!defined('ROOT')) {exit('error!');} 
?>
<aside id="side-overlay">
<div id="side-overlay-scroll">
<div class="content-header content-header-fullrow">
<div class="content-header-section align-parent">
<button type="button" class="btn btn-circle btn-dual-secondary align-v-r" data-toggle="layout" data-action="side_overlay_close">
<i class="fa fa-times text-danger"></i>
</button>
<div class="content-header-item">
<a class="img-link mr-5">
<img class="img-avatar img-avatar32" src="//q1.qlogo.cn/g?b=qq&s=100&nk=<?php echo $udata['qq'];?>" alt="<?php echo $udata['qq'];?>">
</a>
<a class="align-middle link-effect text-primary-dark font-w600"><?php echo $udata['name'];?></a>
</div>
</div>
</div>
<div class="content-side">
<div class="block pull-r-l">
<div class="block-content block-content-full block-content-sm bg-body-light">
<div class="row">
<div class="col-6">
<div class="font-size-sm font-w600 text-uppercase text-muted">访问量</div>
<div class="font-size-h4">未完善</div>
</div>
<div class="col-6">
<div class="font-size-sm font-w600 text-uppercase text-muted">数据量</div>
<div class="font-size-h4">未完善</div>
</div>
</div>
</div>
</div>
<div class="block pull-r-l">
<div class="block-header bg-body-light">
<h3 class="block-title"><i class="fa fa-fw fa-users font-size-default mr-5"></i>站点客服</h3>
<div class="block-options">
<button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
<i class="si si-refresh"></i>
</button>
<button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
</div>
</div>
<div class="block-content">
<ul class="nav-users push">
<li>
<a href="be_pages_generic_profile.php">
<img class="img-avatar" src="//q1.qlogo.cn/g?b=qq&s=100&nk=9571564" alt="9571564">
<i class="fa fa-circle text-success"></i> Andy
<div class="font-w400 font-size-xs text-muted">系统负责人</div>
</a>
</li>
<li>
<a href="be_pages_generic_profile.php">
<img class="img-avatar" src="//q1.qlogo.cn/g?b=qq&s=100&nk=348069510" alt="348069510">
<i class="fa fa-circle text-success"></i> 酸奶
<div class="font-w400 font-size-xs text-muted">攻城狮</div>
</a>
</li>
<li>
<a href="be_pages_generic_profile.php">
<img class="img-avatar" src="//q1.qlogo.cn/g?b=qq&s=100&nk=296505795" alt="296505795">
<i class="fa fa-circle text-success"></i> 阿坤
<div class="font-w400 font-size-xs text-muted">策划者</div>
</a>
</li>
<li>
<a href="be_pages_generic_profile.php">
<img class="img-avatar" src="//q1.qlogo.cn/g?b=qq&s=100&nk=10000" alt="10000">
<i class="fa fa-circle text-danger"></i> 虚以待位
<div class="font-w400 font-size-xs text-muted">虚以待位</div>
</a>
</li>
</ul>
</div>
</div>
</div>
</div>
</aside>
