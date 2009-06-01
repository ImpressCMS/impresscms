-- phpMyAdmin SQL Dump
-- version 2.11.9.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 01, 2009 at 11:52 AM
-- Server version: 5.0.67
-- PHP Version: 5.2.6


#
# Dumping data for table `system_mimetype`
#

INSERT INTO system_mimetype VALUES (1, 'bin', 'application/octet-stream', 'Binary File/Linux Executable');
INSERT INTO system_mimetype VALUES (2, 'dms', 'application/octet-stream', 'Amiga DISKMASHER Compressed Archive');
INSERT INTO system_mimetype VALUES (3, 'class', 'application/octet-stream', 'Java Bytecode');
INSERT INTO system_mimetype VALUES (4, 'so', 'application/octet-stream', 'UNIX Shared Library Function');
INSERT INTO system_mimetype VALUES (5, 'dll', 'application/octet-stream', 'Dynamic Link Library');
INSERT INTO system_mimetype VALUES (6, 'hqx', 'application/binhex application/mac-binhex application/mac-binhex40', 'Macintosh BinHex 4 Compressed Archive');
INSERT INTO system_mimetype VALUES (7, 'cpt', 'application/mac-compactpro application/compact_pro', 'Compact Pro Archive');
INSERT INTO system_mimetype VALUES (8, 'lha', 'application/lha application/x-lha application/octet-stream application/x-compress application/x-compressed application/maclha', 'Compressed Archive File');
INSERT INTO system_mimetype VALUES (9, 'lzh', 'application/lzh application/x-lzh application/x-lha application/x-compress application/x-compressed application/x-lzh-archive zz-application/zz-winassoc-lzh application/maclha application/octet-stream', 'Compressed Archive File');
INSERT INTO system_mimetype VALUES (10, 'sh', 'application/x-shar', 'UNIX shar Archive File');
INSERT INTO system_mimetype VALUES (11, 'shar', 'application/x-shar', 'UNIX shar Archive File');
INSERT INTO system_mimetype VALUES (12, 'tar', 'application/tar application/x-tar applicaton/x-gtar multipart/x-tar application/x-compress application/x-compressed', 'Tape Archive File');
INSERT INTO system_mimetype VALUES (13, 'gtar', 'application/x-gtar', 'GNU tar Compressed File Archive');
INSERT INTO system_mimetype VALUES (14, 'ustar', 'application/x-ustar multipart/x-ustar', 'POSIX tar Compressed Archive');
INSERT INTO system_mimetype VALUES (15, 'zip', 'application/zip application/x-zip application/x-zip-compressed application/octet-stream application/x-compress application/x-compressed multipart/x-zip', 'Compressed Archive File');
INSERT INTO system_mimetype VALUES (16, 'exe', 'application/exe application/x-exe application/dos-exe application/x-winexe application/msdos-windows application/x-msdos-program', 'Executable File');
INSERT INTO system_mimetype VALUES (17, 'wmz', 'application/x-ms-wmz', 'Windows Media Compressed Skin File');
INSERT INTO system_mimetype VALUES (18, 'wmd', 'application/x-ms-wmd', 'Windows Media Download File');
INSERT INTO system_mimetype VALUES (19, 'doc', 'application/msword application/doc appl/text application/vnd.msword application/vnd.ms-word application/winword application/word application/x-msw6 application/x-msword', 'Word Document');
INSERT INTO system_mimetype VALUES (20, 'pdf', 'application/pdf application/acrobat application/x-pdf applications/vnd.pdf text/pdf', 'Acrobat Portable Document Format');
INSERT INTO system_mimetype VALUES (21, 'eps', 'application/eps application/postscript application/x-eps image/eps image/x-eps', 'Encapsulated PostScript');
INSERT INTO system_mimetype VALUES (22, 'ps', 'application/postscript application/ps application/x-postscript application/x-ps text/postscript', 'PostScript');
INSERT INTO system_mimetype VALUES (23, 'smi', 'application/smil', 'SMIL Multimedia');
INSERT INTO system_mimetype VALUES (24, 'smil', 'application/smil', 'Synchronized Multimedia Integration Language');
INSERT INTO system_mimetype VALUES (25, 'wmlc', 'application/vnd.wap.wmlc ', 'Compiled WML Document');
INSERT INTO system_mimetype VALUES (26, 'wmlsc', 'application/vnd.wap.wmlscriptc', 'Compiled WML Script');
INSERT INTO system_mimetype VALUES (27, 'vcd', 'application/x-cdlink', 'Virtual CD-ROM CD Image File');
INSERT INTO system_mimetype VALUES (28, 'pgn', 'application/formstore', 'Picatinny Arsenal Electronic Formstore Form in TIFF Format');
INSERT INTO system_mimetype VALUES (29, 'cpio', 'application/x-cpio', 'UNIX CPIO Archive');
INSERT INTO system_mimetype VALUES (30, 'csh', 'application/x-csh', 'Csh Script');
INSERT INTO system_mimetype VALUES (31, 'dcr', 'application/x-director', 'Shockwave Movie');
INSERT INTO system_mimetype VALUES (32, 'dir', 'application/x-director', 'Macromedia Director Movie');
INSERT INTO system_mimetype VALUES (33, 'dxr', 'application/x-director application/vnd.dxr', 'Macromedia Director Protected Movie File');
INSERT INTO system_mimetype VALUES (34, 'dvi', 'application/x-dvi', 'TeX Device Independent Document');
INSERT INTO system_mimetype VALUES (35, 'spl', 'application/x-futuresplash', 'Macromedia FutureSplash File');
INSERT INTO system_mimetype VALUES (36, 'hdf', 'application/x-hdf', 'Hierarchical Data Format File');
INSERT INTO system_mimetype VALUES (37, 'js', 'application/x-javascript text/javascript', 'JavaScript Source Code');
INSERT INTO system_mimetype VALUES (38, 'skp', 'application/x-koan application/vnd-koan koan/x-skm application/vnd.koan', 'SSEYO Koan Play File');
INSERT INTO system_mimetype VALUES (39, 'skd', 'application/x-koan application/vnd-koan koan/x-skm application/vnd.koan', 'SSEYO Koan Design File');
INSERT INTO system_mimetype VALUES (40, 'skt', 'application/x-koan application/vnd-koan koan/x-skm application/vnd.koan', 'SSEYO Koan Template File');
INSERT INTO system_mimetype VALUES (41, 'skm', 'application/x-koan application/vnd-koan koan/x-skm application/vnd.koan', 'SSEYO Koan Mix File');
INSERT INTO system_mimetype VALUES (42, 'latex', 'application/x-latex text/x-latex', 'LaTeX Source Document');
INSERT INTO system_mimetype VALUES (43, 'nc', 'application/x-netcdf text/x-cdf', 'Unidata netCDF Graphics');
INSERT INTO system_mimetype VALUES (44, 'cdf', 'application/cdf application/x-cdf application/netcdf application/x-netcdf text/cdf text/x-cdf', 'Channel Definition Format');
INSERT INTO system_mimetype VALUES (45, 'swf', 'application/x-shockwave-flash application/x-shockwave-flash2-preview application/futuresplash image/vnd.rn-realflash', 'Macromedia Flash Format File');
INSERT INTO system_mimetype VALUES (46, 'sit', 'application/stuffit application/x-stuffit application/x-sit', 'StuffIt Compressed Archive File');
INSERT INTO system_mimetype VALUES (47, 'tcl', 'application/x-tcl', 'TCL/TK Language Script');
INSERT INTO system_mimetype VALUES (48, 'tex', 'application/x-tex', 'LaTeX Source');
INSERT INTO system_mimetype VALUES (49, 'texinfo', 'application/x-texinfo', 'TeX');
INSERT INTO system_mimetype VALUES (50, 'texi', 'application/x-texinfo', 'TeX');
INSERT INTO system_mimetype VALUES (51, 't', 'application/x-troff', 'TAR Tape Archive Without Compression');
INSERT INTO system_mimetype VALUES (52, 'tr', 'application/x-troff', 'Unix Tape Archive = TAR without compression (tar)');
INSERT INTO system_mimetype VALUES (53, 'src', 'application/x-wais-source', 'Sourcecode');
INSERT INTO system_mimetype VALUES (54, 'xhtml', 'application/xhtml+xml', 'Extensible HyperText Markup Language File');
INSERT INTO system_mimetype VALUES (55, 'xht', 'application/xhtml+xml', 'Extensible HyperText Markup Language File');
INSERT INTO system_mimetype VALUES (56, 'au', 'audio/basic audio/x-basic audio/au audio/x-au audio/x-pn-au audio/rmf audio/x-rmf audio/x-ulaw audio/vnd.qcelp audio/x-gsm audio/snd', 'ULaw/AU Audio File');
INSERT INTO system_mimetype VALUES (57, 'XM', 'audio/xm audio/x-xm audio/module-xm audio/mod audio/x-mod', 'Fast Tracker 2 Extended Module');
INSERT INTO system_mimetype VALUES (58, 'snd', 'audio/basic', 'Macintosh Sound Resource');
INSERT INTO system_mimetype VALUES (59, 'mid', 'audio/mid audio/m audio/midi audio/x-midi application/x-midi audio/soundtrack', 'Musical Instrument Digital Interface MIDI-sequention Sound');
INSERT INTO system_mimetype VALUES (60, 'midi', 'audio/mid audio/m audio/midi audio/x-midi application/x-midi', 'Musical Instrument Digital Interface MIDI-sequention Sound');
INSERT INTO system_mimetype VALUES (61, 'kar', 'audio/midi audio/x-midi audio/mid x-music/x-midi', 'Karaoke MIDI File');
INSERT INTO system_mimetype VALUES (62, 'mpga', 'audio/mpeg audio/mp3 audio/mgp audio/m-mpeg audio/x-mp3 audio/x-mpeg audio/x-mpg video/mpeg', 'Mpeg-1 Layer3 Audio Stream');
INSERT INTO system_mimetype VALUES (63, 'mp2', 'video/mpeg audio/mpeg', 'MPEG Audio Stream, Layer II');
INSERT INTO system_mimetype VALUES (64, 'mp3', 'audio/mpeg audio/x-mpeg audio/mp3 audio/x-mp3 audio/mpeg3 audio/x-mpeg3 audio/mpg audio/x-mpg audio/x-mpegaudio', 'MPEG Audio Stream, Layer III');
INSERT INTO system_mimetype VALUES (65, 'aif', 'audio/aiff audio/x-aiff sound/aiff audio/rmf audio/x-rmf audio/x-pn-aiff audio/x-gsm audio/x-midi audio/vnd.qcelp', 'Audio Interchange File');
INSERT INTO system_mimetype VALUES (66, 'aiff', 'audio/aiff audio/x-aiff sound/aiff audio/rmf audio/x-rmf audio/x-pn-aiff audio/x-gsm audio/mid audio/x-midi audio/vnd.qcelp', 'Audio Interchange File');
INSERT INTO system_mimetype VALUES (67, 'aifc', 'audio/aiff audio/x-aiff audio/x-aifc sound/aiff audio/rmf audio/x-rmf audio/x-pn-aiff audio/x-gsm audio/x-midi audio/mid audio/vnd.qcelp', 'Audio Interchange File');
INSERT INTO system_mimetype VALUES (68, 'm3u', 'audio/x-mpegurl audio/mpeg-url application/x-winamp-playlist audio/scpls audio/x-scpls', 'MP3 Playlist File');
INSERT INTO system_mimetype VALUES (69, 'ram', 'audio/x-pn-realaudio audio/vnd.rn-realaudio audio/x-pm-realaudio-plugin audio/x-pn-realvideo audio/x-realaudio video/x-pn-realvideo text/plain', 'RealMedia Metafile');
INSERT INTO system_mimetype VALUES (70, 'rm', 'application/vnd.rn-realmedia audio/vnd.rn-realaudio audio/x-pn-realaudio audio/x-realaudio audio/x-pm-realaudio-plugin', 'RealMedia Streaming Media');
INSERT INTO system_mimetype VALUES (71, 'rpm', 'audio/x-pn-realaudio audio/x-pn-realaudio-plugin audio/x-pnrealaudio-plugin video/x-pn-realvideo-plugin audio/x-mpegurl application/octet-stream', 'RealMedia Player Plug-in');
INSERT INTO system_mimetype VALUES (72, 'ra', 'audio/vnd.rn-realaudio audio/x-pn-realaudio audio/x-realaudio audio/x-pm-realaudio-plugin video/x-pn-realvideo', 'RealMedia Streaming Media');
INSERT INTO system_mimetype VALUES (73, 'wav', 'audio/wav audio/x-wav audio/wave audio/x-pn-wav', 'Waveform Audio');
INSERT INTO system_mimetype VALUES (74, 'wax', ' audio/x-ms-wax', 'Windows Media Audio Redirector');
INSERT INTO system_mimetype VALUES (75, 'wma', 'audio/x-ms-wma video/x-ms-asf', 'Windows Media Audio File');
INSERT INTO system_mimetype VALUES (76, 'bmp', 'image/bmp image/x-bmp image/x-bitmap image/x-xbitmap image/x-win-bitmap image/x-windows-bmp image/ms-bmp image/x-ms-bmp application/bmp application/x-bmp application/x-win-bitmap application/preview', 'Windows OS/2 Bitmap Graphics');
INSERT INTO system_mimetype VALUES (77, 'gif', 'image/gif image/x-xbitmap image/gi_', 'Graphic Interchange Format');
INSERT INTO system_mimetype VALUES (78, 'ief', 'image/ief', 'Image File - Bitmap graphics');
INSERT INTO system_mimetype VALUES (79, 'jpeg', 'image/jpeg image/jpg image/jpe_ image/pjpeg image/vnd.swiftview-jpeg', 'JPEG/JIFF Image');
INSERT INTO system_mimetype VALUES (80, 'jpg', 'image/jpeg image/jpg image/jp_ application/jpg application/x-jpg image/pjpeg image/pipeg image/vnd.swiftview-jpeg image/x-xbitmap', 'JPEG/JIFF Image');
INSERT INTO system_mimetype VALUES (81, 'jpe', 'image/jpeg', 'JPEG/JIFF Image');
INSERT INTO system_mimetype VALUES (82, 'png', 'image/png application/png application/x-png', 'Portable (Public) Network Graphic');
INSERT INTO system_mimetype VALUES (83, 'tiff', 'image/tiff', 'Tagged Image Format File');
INSERT INTO system_mimetype VALUES (84, 'tif', 'image/tif image/x-tif image/tiff image/x-tiff application/tif application/x-tif application/tiff application/x-tiff', 'Tagged Image Format File');
INSERT INTO system_mimetype VALUES (85, 'ico', 'image/ico image/x-icon application/ico application/x-ico application/x-win-bitmap image/x-win-bitmap application/octet-stream', 'Windows Icon');
INSERT INTO system_mimetype VALUES (86, 'wbmp', 'image/vnd.wap.wbmp', 'Wireless Bitmap File Format');
INSERT INTO system_mimetype VALUES (87, 'ras', 'application/ras application/x-ras image/ras', 'Sun Raster Graphic');
INSERT INTO system_mimetype VALUES (88, 'pnm', 'image/x-portable-anymap', 'PBM Portable Any Map Graphic Bitmap');
INSERT INTO system_mimetype VALUES (89, 'pbm', 'image/portable bitmap image/x-portable-bitmap image/pbm image/x-pbm', 'UNIX Portable Bitmap Graphic');
INSERT INTO system_mimetype VALUES (90, 'pgm', 'image/x-portable-graymap image/x-pgm', 'Portable Graymap Graphic');
INSERT INTO system_mimetype VALUES (91, 'ppm', 'image/x-portable-pixmap application/ppm application/x-ppm image/x-p image/x-ppm', 'PBM Portable Pixelmap Graphic');
INSERT INTO system_mimetype VALUES (92, 'rgb', 'image/rgb image/x-rgb', 'Silicon Graphics RGB Bitmap');
INSERT INTO system_mimetype VALUES (93, 'xbm', 'image/x-xpixmap image/x-xbitmap image/xpm image/x-xpm', 'X Bitmap Graphic');
INSERT INTO system_mimetype VALUES (94, 'xpm', 'image/x-xpixmap', 'BMC Software Patrol UNIX Icon File');
INSERT INTO system_mimetype VALUES (95, 'xwd', 'image/x-xwindowdump image/xwd image/x-xwd application/xwd application/x-xwd', 'X Windows Dump');
INSERT INTO system_mimetype VALUES (96, 'igs', 'model/iges application/iges application/x-iges application/igs application/x-igs drawing/x-igs image/x-igs', 'Initial Graphics Exchange Specification Format');
INSERT INTO system_mimetype VALUES (97, 'css', 'application/css-stylesheet text/css', 'Hypertext Cascading Style Sheet');
INSERT INTO system_mimetype VALUES (98, 'html', 'text/html text/plain', 'Hypertext Markup Language');
INSERT INTO system_mimetype VALUES (99, 'htm', 'text/html', 'Hypertext Markup Language');
INSERT INTO system_mimetype VALUES (100, 'txt', 'text/plain application/txt browser/internal', 'Text File');
INSERT INTO system_mimetype VALUES (101, 'rtf', 'application/rtf application/x-rtf text/rtf text/richtext application/msword application/doc application/x-soffice', 'Rich Text Format File');
INSERT INTO system_mimetype VALUES (102, 'wml', 'text/vnd.wap.wml text/wml', 'Website META Language File');
INSERT INTO system_mimetype VALUES (103, 'wmls', 'text/vnd.wap.wmlscript', 'WML Script');
INSERT INTO system_mimetype VALUES (104, 'etx', 'text/x-setext', 'SetText Structure Enhanced Text');
INSERT INTO system_mimetype VALUES (105, 'xml', 'text/xml application/xml application/x-xml', 'Extensible Markup Language File');
INSERT INTO system_mimetype VALUES (106, 'xsl', 'text/xml', 'XML Stylesheet');
INSERT INTO system_mimetype VALUES (107, 'php', 'text/php application/x-httpd-php application/php magnus-internal/shellcgi application/x-php', 'PHP Script');
INSERT INTO system_mimetype VALUES (108, 'php3', 'text/php3 application/x-httpd-php', 'PHP Script');
INSERT INTO system_mimetype VALUES (109, 'mpeg', 'video/mpeg', 'MPEG Movie');
INSERT INTO system_mimetype VALUES (110, 'mpg', 'video/mpeg video/mpg video/x-mpg video/mpeg2 application/x-pn-mpg video/x-mpeg video/x-mpeg2a audio/mpeg audio/x-mpeg image/mpg', 'MPEG 1 System Stream');
INSERT INTO system_mimetype VALUES (111, 'mpe', 'video/mpeg', 'MPEG Movie Clip');
INSERT INTO system_mimetype VALUES (112, 'qt', 'video/quicktime audio/aiff audio/x-wav video/flc', 'QuickTime Movie');
INSERT INTO system_mimetype VALUES (113, 'mov', 'video/quicktime video/x-quicktime image/mov audio/aiff audio/x-midi audio/x-wav video/avi', 'QuickTime Video Clip');
INSERT INTO system_mimetype VALUES (114, 'avi', 'video/avi video/msvideo video/x-msvideo image/avi video/xmpg2 application/x-troff-msvideo audio/aiff audio/avi', 'Audio Video Interleave File');
INSERT INTO system_mimetype VALUES (115, 'movie', 'video/sgi-movie video/x-sgi-movie', 'QuickTime Movie');
INSERT INTO system_mimetype VALUES (116, 'asf', 'audio/asf application/asx video/x-ms-asf-plugin application/x-mplayer2 video/x-ms-asf application/vnd.ms-asf video/x-ms-asf-plugin video/x-ms-wm video/x-ms-wmx', 'Advanced Streaming Format');
INSERT INTO system_mimetype VALUES (117, 'asx', 'video/asx application/asx video/x-ms-asf-plugin application/x-mplayer2 video/x-ms-asf application/vnd.ms-asf video/x-ms-asf-plugin video/x-ms-wm video/x-ms-wmx video/x-la-asf', 'Advanced Stream Redirector File');
INSERT INTO system_mimetype VALUES (118, 'wmv', 'video/x-ms-wmv', 'Windows Media File');
INSERT INTO system_mimetype VALUES (119, 'wvx', 'video/x-ms-wvx', 'Windows Media Redirector');
INSERT INTO system_mimetype VALUES (120, 'wm', 'video/x-ms-wm', 'Windows Media A/V File');
INSERT INTO system_mimetype VALUES (121, 'wmx', 'video/x-ms-wmx', 'Windows Media Player A/V Shortcut');
INSERT INTO system_mimetype VALUES (122, 'ice', 'x-conference-xcooltalk', 'Cooltalk Audio');
INSERT INTO system_mimetype VALUES (123, 'rar', 'application/octet-stream', 'WinRAR Compressed Archive');
INSERT INTO `group_permission` (`gperm_id`, `gperm_groupid`, `gperm_itemid`, `gperm_modid`, `gperm_name`) VALUES
(NULL, 2, 20, 1, 'use_extension'),
(NULL, 1, 20, 1, 'use_extension'),
(NULL, 2, 19, 1, 'use_extension'),
(NULL, 1, 19, 1, 'use_extension'),
(NULL, 2, 76, 1, 'use_extension'),
(NULL, 1, 76, 1, 'use_extension'),
(NULL, 2, 77, 1, 'use_extension'),
(NULL, 1, 77, 1, 'use_extension'),
(NULL, 2, 82, 1, 'use_extension'),
(NULL, 1, 82, 1, 'use_extension'),
(NULL, 2, 79, 1, 'use_extension'),
(NULL, 1, 79, 1, 'use_extension'),
(NULL, 2, 80, 1, 'use_extension'),
(NULL, 1, 80, 1, 'use_extension'),
(NULL, 2, 81, 1, 'use_extension'),
(NULL, 1, 81, 1, 'use_extension'),
(NULL, 2, 83, 1, 'use_extension'),
(NULL, 1, 83, 1, 'use_extension'),
(NULL, 2, 84, 1, 'use_extension'),
(NULL, 1, 84, 1, 'use_extension'),
(NULL, 2, 100, 1, 'use_extension'),
(NULL, 1, 100, 1, 'use_extension'),
(NULL, 2, 101, 1, 'use_extension'),
(NULL, 1, 101, 1, 'use_extension');
