# Website dầu ăn đã qua sử dụng — Ánh Trang

Source WordPress hiện tại cho https://dauandaquasudung.com/, gồm core, Flatsome/child theme, plugin và uploads. Chạy bằng PHP 7.4; table prefix `bz_`.

## Khôi phục
1. Triển khai source lên hosting; giữ cấu hình riêng của hosting. File `wp-config.php` thật không được theo dõi trong Git. Nếu cần tạo mới, dùng `wp-config.example.php` và cấu hình các biến môi trường DB/salt tương ứng.
2. Database mới nhất nằm trong `database/latest.sql.gz.enc`, mã hóa AES-256-GCM và nén gzip. Khóa giải mã được giữ riêng ngoài repository.
3. Giải mã bằng PHP CLI có OpenSSL/zlib:
   `php tools/restore-database.php --key-file=/private/database-key.txt --output=/private/restore.sql`
4. Sao lưu database hosting rồi import file SQL đã giải mã vào database được chọn. Bản xuất có lệnh thay các bảng `bz_` đang tồn tại.
5. Giữ file SQL và khóa ngoài web root. Xóa cache hosting/CDN và lưu lại Đường dẫn tĩnh trong quản trị.

## Chức năng đã cập nhật
- Cài đặt → Thông tin liên hệ: quản lý tên công ty, mô tả, điện thoại, email, địa chỉ, ba miền và đại diện riêng.
- `[anhtrang_contact]`: khối liên hệ chung; 60 bài viết dùng một khối mỗi bài.
- `[anhtrang_contact_field field="phone" format="link"]`: lấy từng trường; hỗ trợ text, link, tel, mailto, maps, url.
- Dòng mô tả in nghiêng ngay dưới tên công ty; CSS nổi bật ở cuối bài và responsive trên điện thoại.
- Plugin `anhtrang-site-icons` khôi phục các icon bị thiếu của trang chủ.

Runtime localhost, bản sao lưu, file cấu hình có mật khẩu, khóa giải mã và bộ ZIP triển khai không được commit. Manifest database ghi thời gian xuất và SHA-256 để đối chiếu.
