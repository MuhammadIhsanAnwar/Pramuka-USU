<?php

namespace App\Enums;

enum PermissionName: string
{
    case UsersView = 'users.view';
    case UsersCreate = 'users.create';
    case UsersUpdate = 'users.update';
    case UsersDelete = 'users.delete';
    case UsersChangeRole = 'users.change-role';

    case NewsView = 'news.view';
    case NewsCreate = 'news.create';
    case NewsUpdate = 'news.update';
    case NewsDelete = 'news.delete';
    case NewsPublish = 'news.publish';
    case NewsManage = 'news.manage';
    case NewsOwnManage = 'news.own.manage';

    case AgendaView = 'agenda.view';
    case AgendaCreate = 'agenda.create';
    case AgendaUpdate = 'agenda.update';
    case AgendaDelete = 'agenda.delete';
    case AgendaManage = 'agenda.manage';

    case GalleryView = 'gallery.view';
    case GalleryCreate = 'gallery.create';
    case GalleryUpdate = 'gallery.update';
    case GalleryDelete = 'gallery.delete';
    case GalleryManage = 'gallery.manage';

    case AttendanceView = 'attendance.view';
    case AttendanceCreate = 'attendance.create';
    case AttendanceManage = 'attendance.manage';
    case AttendanceQrCreate = 'attendance.qr.create';
    case AttendanceScan = 'attendance.scan';
    case AttendanceHistoryView = 'attendance.history.view';

    case ReportsView = 'reports.view';
    case ReportsExportPdf = 'reports.export-pdf';
    case ReportsExportExcel = 'reports.export-excel';

    case WebsiteManage = 'website.manage';
    case ProfileUpdate = 'profile.update';
}