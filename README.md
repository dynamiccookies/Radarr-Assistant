# Radarr-Assistant
![GitHub](https://img.shields.io/github/license/dynamiccookies/Radarr-Assistant?style=for-the-badge "License")
![GitHub Release Date](https://img.shields.io/github/release-date/dynamiccookies/Radarr-Assistant?style=for-the-badge "Release Date")
![GitHub release (latest SemVer including pre-releases)](https://img.shields.io/github/v/release/dynamiccookies/radarr-assistant?display_name=tag&include_prereleases&sort=semver&style=for-the-badge "Release Version")
[<img alt="Deployed with FTP Deploy Action" src="https://img.shields.io/badge/Deployed With-Simple FTP deploy-%3CCOLOR%3E?style=for-the-badge&color=0077b6">](https://github.com/kevinpainchaud/simple-ftp-deploy-action)


A webpage integrated with Radarr's API that allows users to search for movies, verify movies currently in the Radarr library, and add new movies to Radarr's queue all without giving direct access to Radarr itself. 

**Note:**
- This integration utilizes IFTTT's Webhooks to connect from an exterally hosted web server to an internally hosted Radarr server.
- This is **NOT** needed if you can utilize PHP cURL to directly call Radarr from your web server
- However, if you do need to use IFTTT, you will need to create an account and build three integrations (documentation coming soon)

## Example
![image](https://user-images.githubusercontent.com/9450183/188695790-01b32b94-9b26-48e4-9016-83e94be64809.png)

## Prerequisites
- Radarr server
- Web server
  - Must be able to access Radarr server

## How to Install
- Download the latest [release](releases)
- Unzip the release
- Open the admin folder
- Open the the [config.php](blob/main/admin/config.php) file in a text editor
- Fill in the global variables: `$debug`, `$https`, `$ip`, `$port`, `$radarr_api_key`, `$ifttt_api_key`
https://github.com/dynamiccookies/Radarr-Assistant/blob/5d92f7a75fa6922dba754ee1697e8be788bd29a5/admin/config.php#L1-L8
- Upload the release files to your web server

And you're done!


## License

This project uses the [MIT license](LICENSE).
