# Database mới nhất

`latest.sql.gz.enc` chứa bản SQL đầy đủ mới nhất dành cho tên miền production, gồm các bài viết, UX Block, thiết lập theme/plugin và thông tin liên hệ đã cập nhật.

File được mã hóa AES-256-GCM với khóa 256 bit giữ riêng trên máy người quản trị; không có khóa trong Git. Dùng `tools/restore-database.php` và xem hướng dẫn ở README gốc. File SQL sau giải mã là dữ liệu riêng tư, không commit hoặc đặt trên web công khai.

`manifest.json` chứa thời điểm xuất và checksum cho bản mã lẫn SQL gốc. Cache/phiên đăng nhập thử nghiệm local được loại khỏi bản xuất.
