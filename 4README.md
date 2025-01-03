
!!!!!!!!!!!!!!!!!하단의 readme로 가시오!!!!!!!!!!!!!!!!!

# 소스 코드 제출양식 : github public 
소스 코드와 작성한 프로그램을 제출합니다. 
Github에 public 으로 업로드하세요.
* 평가가 종료되면 private으로 변경하는 것을 권장합니다..
접근가능한 링크를 공유해주세요.
	
# 최종 발표 동영상
동영상은 여러분이 작성한 최종 프로젝트의 결과를 발표하는 자리입니다. 최종 발표는 기록 유지와 공유를 위해 동영상으로 대체합니다. 해당 동영상에는 아래와 같은 내용이 필수로 포함되어야 하며, 그외의 형식은 자유입니다. 
프로그램의 제작 동기 (해결하고자 하는 문제)
관련 소프트웨어 및 연구
제안하는 소프트웨어가 문제를 해결하는 방법 (알고리즘)
다른 소프트웨어 및 연구와의 차이점
작성한 프로그램의 시연

동영상 제작시 주의할 사항은 아래와 같습니다.
동영상의 길이는 10분을 초과하지 않도록 합니다. 반대로 너무 짧지 않도록 유의해주세요.
동영상은 본인의 유튜브 계정에 올려 링크를 공유해주기 바랍니다. 
화질이 너무 떨어지지 않도록 해주세요.
스마트 폰으로 화면을 촬영하는 방식으로 하면 영상의 질이 좋지 않습니다. 화면을 녹화(screen recording)해주세요. 여러가지 툴들이 있습니다.

1. Setting Up the Server Environment -> mysql 
# Update system packages
sudo apt update && sudo apt upgrade -y %% sudo apt install nano -y (nano 편집기 -> vi 느낌)&& sudo apt install ufw -y (방화벽)
# Install Apache Web Server
sudo apt install apache2 -y
# Install PHP and required extensions
sudo apt install php libapache2-mod-php php-mysql -y
sudo apt install apache2 php libapache2-mod-php php-mysql mysql-server -y
# Install MySQL Server
sudo apt install mysql-server -y
# Start services
sudo systemctl start apache2
sudo systemctl start mysql
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
## ide_groom 디비 삭제완료 
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


# 라이브 서버의 경우:
호스팅 공급자(예: AWS, DigitalOcean 또는 웹 호스팅 서비스)를 사용합니다.
설치:
Apache/Nginx: HTTP 요청을 처리하는 웹 서버입니다.
PHP: 애플리케이션을 실행합니다.
MySQL: 데이터베이스용.

# How to commit to github
git init
git add .
git commit -m "messages blah blah"
git remote add origin https://github.com/cadetB/test.git
# 스테이지에 추가된 파일에 대한 master에 커밋하기 실행 (with message)
# push 실패하면 다음 명령어를 터미널에서 실행 
git push -u origin master
git config --global user.name "cadetB"
 or git config --global user.email "your-email@example.com"
Password for 'https://cadetB@github.com': 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

1. 구름에서 작업 후 각 파일 저장
2. 자동으로 스테이지에서 제외된 파일로 감
3. + 눌러서 스테이지에 추가된 파일로 이동 
4. commit 메시지 입력 후 master 에 커밋하기 
5. commit 오류나면 명령어 실행 with token 
=============================================================
[V]K_view_records: 점수 입력칸 삭제(코드 수정 필요)
[ ]기입 정보 엑셀 파일 저장 기능 추가(코드 수정 필요)
★새 터미널 열기 단축키: alt+shift+T(mac: ​⌥⇧T)

## Total
- 총점 자동 계산 
- 분야별 환산점수 자동계산(사용자가 직접 계산 x)
	ex. 토익 환산점수, 지난학기 대비 환산점수
## 사용자 관점
- 국방인사정보체계처럼 누적이 되어 그래프, 표, 점수 등 데이터 제공하면 좋을듯 -> 개인자력, 활동 등 관리 ("생도생활 이렇게 했다~ 장단점 이거다~)
- 인증자료 업로드 -> 스캔, 캡처 
## 관리자 관점
- 인증: 눈으로 보기만 하면 되서 얼마 안걸림 (서면)
==================================================================================================================================================================================================
1. 과제소개(classroom)
- 제출물: 최종보고서 + 소스코드 + 발표동영상 10분
최종보고서
최종보고서는 여러분의 최종 프로젝트를 문서로 설명하고, 여러분의 프로젝트가 얼마나 가치있는지를 글로써 표현하는 과제이며, 아래와 같은 사항을 준수하세요
프로그램의 제작 동기 (해결하고자 하는 문제)
관련 소프트웨어 및 연구
제안하는 소프트웨어가 문제를 해결하는 방법 (알고리즘)
다른 소프트웨어 및 연구와의 차이점
시간계획 (마일즈스톤)
임무분장 
개발한 소프트웨어를 통해 해결한 문제와 제한사항
참고문헌 (아래 링크 참고)
* https://library.khu.ac.kr/seoul/referencingNcitation/chicago
보고서 작성 시 사용하는 소프트웨어는 자유지만 overleaf(latex) 를 권장
* https://www.overleaf.com/

최종보고서 작성 시 유의사항은 아래와 같습니다.

제출 포맷은 pdf 입니다.
위의 구성 요소를 누락하지 않도록 유의하세요.
관련 소프트웨어 및 연구는 본인이 만들고자 하는 소프트웨어와 유사한 소프트웨어 또는 연구를 조사하는 것입니다. 파이썬 등을 소개하라는 것이 아닙니다.
IEEE conference 포맷을 사용합니다. overleaf에 있는 포맷을 참고하세요:
* https://www.overleaf.com/latex/templates/ieee-conference-template/grfzhhncsfqn
=================================================================================================================================================================================================
소스 코드
소스 코드와 작성한 프로그램을 제출합니다. 
Github에 public 으로 업로드하세요.
* 평가가 종료되면 private으로 변경하는 것을 권장합니다.
접근가능한 링크를 공유해주세요.
===================================================================================================================================================================================================
최종 발표 동영상
동영상은 여러분이 작성한 최종 프로젝트의 결과를 발표하는 자리입니다. 최종 발표는 기록 유지와 공유를 위해 동영상으로 대체합니다. 해당 동영상에는 아래와 같은 내용이 필수로 포함되어야 하며, 그외의 형식은 자유입니다. 
프로그램의 제작 동기 (해결하고자 하는 문제)
관련 소프트웨어 및 연구
제안하는 소프트웨어가 문제를 해결하는 방법 (알고리즘)
다른 소프트웨어 및 연구와의 차이점
작성한 프로그램의 시연

동영상 제작시 주의할 사항은 아래와 같습니다.
동영상의 길이는 10분을 초과하지 않도록 합니다. 반대로 너무 짧지 않도록 유의해주세요.
동영상은 본인의 유튜브 계정에 올려 링크를 공유해주기 바랍니다. 
화질이 너무 떨어지지 않도록 해주세요.
스마트 폰으로 화면을 촬영하는 방식으로 하면 영상의 질이 좋지 않습니다. 화면을 녹화(screen recording)해주세요. 여러가지 툴들이 있습니다.
======================================================================================================================================================================================================


2. 개요(Notion 참고) 링크: https://classic-poet-a4b.notion.site/3ebd55038ffd47d6af865cc1358ab488?pvs=4




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

테이블 일일이 구성하지 말고 테이블 필요한 파일을 GPT에 첨부해서 기능에 맞는 테이블 만드는 명령어 알려달라고 할 예정

