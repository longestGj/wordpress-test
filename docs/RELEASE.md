# 发布

ACCEPTED 不等于发布。当前环境是 localhost Docker，保持 noindex；远端推送不等于上线。

正式发布需要用户明确授权，以及可恢复的数据库、uploads 与代码备份。Git 不包含后台内容和询盘数据。上线前验证实际域名、HTTPS、canonical、robots、sitemap、404、隐私/同意行为和真实表单接收。

RFQ、Sample、Documents 接收流程必须独立验收。邮件接收已单独开发，发布前仍需验证正式环境的真实收件与失败处理，不因页面完成自动启用。全局 RFQ 仍指向未完成页面时不得发布。

部署前记录已测试的提交及数据库迁移命令；部署后检查 Home、Product、Application、Resource、RFQ、手机和 SEO。失败时按事先验证的回退方案处理。仓库重命名、旧仓库归档、DNS 和索引操作不包含在本次本地策划整合中。

GA4 `G-SY6PZPX0VR` 只在可索引的 `https://tio2products.com/` 上显示同意控件。上线前核对 GA4 媒体资源的数据保留和增强型衡量设置，尤其是表单互动。上线验收要在全新浏览器中确认：选择前及拒绝后无 Google 统计请求，同意后 GA4 实时报告收到页面访问，撤回后新页面不再加载 Google 代码且 GA4 Cookie 已清理。先在隔离环境运行 `scripts/migrate-analytics-policy.php` 的 dry-run，正式内容迁移需单独授权。GSC 验证文件 `googleaa2e91750b47f47a.html` 应持续在域名根目录可访问；Search Console 的 URL 前缀资源需要在账号内完成验证，正式开启索引后再提交 sitemap。
