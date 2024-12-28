1. Setting Up the Server Environment -> mysql 
# Update system packages
sudo apt update && sudo apt upgrade -y %% sudo apt install nano -y (nano 편집기 -> vi 느낌)&& sudo apt install ufw -y (방화벽)

# Install Apache Web Server
sudo apt install apache2 -y
# Install PHP and required extensions
sudo apt install php libapache2-mod-php php-mysql -y
# Install MySQL Server
sudo apt install mysql-server -y
# Secure MySQL installation
sudo mysql_secure_installation
#password = 1234  (sudo mysql -u root -p  : 접속 명령어)
USE database_name;
	mysql> show tables;
	+---------------------+
	| Tables_in_ide_groom |
	+---------------------+
	| data                |
	+---------------------+
	1 row in set (0.00 sec)

	mysql> select * from data;
	Empty set (0.00 sec)

	mysql> desc data;
	+-------------+--------------+------+-----+-------------------+-----------------------------+
	| Field       | Type         | Null | Key | Default           | Extra                       |
	+-------------+--------------+------+-----+-------------------+-----------------------------+
	| id          | int(11)      | NO   | PRI | NULL              | auto_increment              |
	| title       | varchar(255) | NO   |     | NULL              |                             |
	| description | text         | NO   |     | NULL              |                             |
	| created_at  | timestamp    | NO   |     | CURRENT_TIMESTAMP |                             |
	| updated_at  | timestamp    | NO   |     | CURRENT_TIMESTAMP | on update CURRENT_TIMESTAMP |
	+-------------+--------------+------+-----+-------------------+-----------------------------+
	5 rows in set (0.00 sec)

## ide_groom 디비 삭제오나료 

# Flow Diagram
1. Frontend:
HTML forms for input
JavaScript for interactivity
Dynamic tables to display data
2. Backend:
PHP handles CRUD operations.
MySQL stores data.
3. Deployment:
Hosted on Apache with URL routing.


User Input ---> PHP Scripts ---> MySQL Database
   ^                                  |
   |                                  v
HTML Page <--- JSON Response <--- Fetch Queries


################################
# 라이브 서버의 경우:
호스팅 공급자(예: AWS, DigitalOcean 또는 웹 호스팅 서비스)를 사용합니다.
설치:
Apache/Nginx: HTTP 요청을 처리하는 웹 서버입니다.
PHP: 애플리케이션을 실행합니다.
MySQL: 데이터베이스용.
# Update and install packages
sudo apt update && sudo apt upgrade -y
sudo apt install apache2 php libapache2-mod-php php-mysql mysql-server -y

# Start services
sudo systemctl start apache2
sudo systemctl start mysql

==============================================

(선호재)
register에서 등록 성공하면 login으로 넘어가도록 register 코드 약간 수정
login에서 이메일과 패스워드로 로그인 가능하도록 코드 설정
GPT에서는 모든 파일이 index와 연동될 필요는 없어보인다고 판단 -> 각 파일이 데이터베이스와 연동되도록만 하면 됨
### SSH/포트포워딩 기능은 컨테이너가 동작 중일 때 사용할 수 있고 컨테이너 재실행 시 IP와 Port가 변경됩니다.
## -> $servername = "13.209.161.15" / $port = 55876; 변동해야함!!! 컨테이너 새로 실행할 때마다
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database_name";



