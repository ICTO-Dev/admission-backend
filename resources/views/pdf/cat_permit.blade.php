<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>CBSUA CAT Test Permit - {{ $applicationNo }}</title>
    <style type="text/css">
        @page {
            size: letter portrait;
            margin: 12mm 15mm 12mm 15mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 9.5pt;
            color: #111827;
            margin: 0;
            padding: 0;
            line-height: 1.25;
        }

        /* Institutional Header */
        table.header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table.header-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }

        .rep-title {
            font-size: 8.5pt;
            font-weight: normal;
            text-transform: uppercase;
            color: #4b5563;
            margin: 0;
        }

        .univ-title {
            font-size: 12pt;
            font-weight: bold;
            color: #064e3b;
            margin: 1px 0 2px 0;
        }

        .univ-sub {
            font-size: 8pt;
            color: #374151;
            margin: 0;
            line-height: 1.2;
        }

        /* Form Table */
        table.tborder {
            border-collapse: collapse;
            width: 100%;
            border: 1.5pt solid #000000;
        }

        table.tborder td {
            border: 0.75pt solid #000000;
            padding: 4px 6px;
            font-size: 9pt;
            vertical-align: middle;
        }

        .title-cell {
            background-color: #f0fdf4;
            font-weight: bold;
            font-size: 10pt;
            text-align: center;
            color: #064e3b;
            padding: 6px !important;
        }

        .app-no-cell {
            background-color: #ffffff;
            font-size: 8.5pt;
            text-align: right;
            padding: 6px !important;
        }

        .label-cell {
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #374151;
            background-color: #f9fafb;
        }

        .val-cell {
            font-size: 9pt;
            font-weight: bold;
            color: #000000;
        }

        .section-header-cell {
            background-color: #e5e7eb;
            font-weight: bold;
            font-size: 8.5pt;
            text-transform: uppercase;
            padding: 4px 6px !important;
        }

        .photo-td {
            width: 130px;
            height: 155px;
            text-align: center;
            vertical-align: middle;
            background-color: #ffffff;
            padding: 4px !important;
        }

        .photo-img {
            width: 120px;
            height: 145px;
            object-fit: cover;
        }

        .photo-placeholder {
            width: 120px;
            height: 145px;
            border: 1pt dashed #9ca3af;
            margin: 0 auto;
            padding-top: 45px;
            font-size: 7.5pt;
            color: #6b7280;
            text-align: center;
            background-color: #fafafa;
        }

        /* Signatures */
        .sig-td {
            height: 38px;
            vertical-align: bottom;
            text-align: center;
            padding-bottom: 4px !important;
        }

        .sig-line {
            border-bottom: 0.75pt solid #000000;
            width: 80%;
            margin: 0 auto;
        }

        .sig-caption {
            font-size: 7pt;
            color: #4b5563;
            margin-top: 2px;
        }

        /* Notes & Footers */
        .notes-area {
            margin-top: 8px;
            font-size: 8pt;
            color: #374151;
            line-height: 1.35;
        }

        .admin-footer {
            margin-top: 14px;
            border-top: 1pt solid #000000;
            padding-top: 3px;
        }

        .admin-footer table {
            width: 100%;
            border-collapse: collapse;
        }

        .admin-footer td {
            font-size: 7.5pt;
            color: #000000;
            border: none;
            padding: 1px 0;
        }
    </style>
</head>
<body>

    <!-- Header with Official CBSUA Seal & ISO Badge -->
    <table class="header-table">
        <tr>
            <td width="75" align="center" style="vertical-align: middle;">
                @if(!empty($cbsuaLogoBase64))
                    <img src="{{ $cbsuaLogoBase64 }}" width="68" height="68" alt="CBSUA Seal" style="display: block; margin: 0 auto;" />
                @endif
            </td>
            <td style="padding-left: 10px; vertical-align: middle;">
                <p class="rep-title">Republic of the Philippines</p>
                <div class="univ-title">CENTRAL BICOL STATE UNIVERSITY OF AGRICULTURE</div>
                <p class="univ-sub">
                    {{ $campusAddress }}<br>
                    Website: <strong>www.cbsua.edu.ph</strong> | Email: <strong>{{ $coordinatorEmail }}</strong><br>
                    Trunkline: <strong>{{ $coordinatorContact }}</strong>
                </p>
            </td>
            <td width="90" align="right" style="vertical-align: middle;">
                <table style="width: 85px; border: 1pt solid #064e3b; border-collapse: collapse; text-align: center;">
                    <tr>
                        <td style="border: none; padding: 4px 2px; background-color: #ffffff; text-align: center;">
                            <div style="font-size: 7pt; font-weight: bold; color: #064e3b;">ISO 9001:2015</div>
                            <div style="font-size: 6pt; color: #4b5563; font-weight: bold;">CERTIFIED</div>
                            <div style="font-size: 5pt; color: #9ca3af;">LEVEL IV</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Main Examination Permit Table (ADM-FR-005) -->
    <table class="tborder">
        <!-- Row 1: Title and App No -->
        <tr>
            <td colspan="6" class="title-cell">
                CBSUA - COLLEGE ADMISSION TEST (CAT) PERMIT
            </td>
            <td colspan="2" class="app-no-cell">
                APPLICATION NO. <strong style="font-size: 10pt; color: #064e3b;">{{ $applicationNo }}</strong>
            </td>
        </tr>

        <!-- Row 2: Printed Name and Photo Box (Rowspan) -->
        <tr>
            <td class="label-cell" colspan="2" width="28%">PRINTED NAME (last, First, MI)</td>
            <td colspan="4" class="val-cell" width="48%">
                <strong style="font-size: 10pt;">{{ $printedName }}</strong>
            </td>
            <td rowspan="4" colspan="2" class="photo-td" width="24%">
                @if(!empty($photoBase64))
                    <img src="{{ $photoBase64 }}" class="photo-img" alt="Applicant Photo" />
                @elseif(!empty($photoUrl))
                    <img src="{{ $photoUrl }}" class="photo-img" alt="Applicant Photo" />
                @else
                    <div class="photo-placeholder">
                        Attach 2x2 ID Photo<br>(White Background)
                    </div>
                @endif
            </td>
        </tr>

        <!-- Row 3: Date of Birth, Age, Contact No -->
        <tr>
            <td class="label-cell">DATE OF BIRTH</td>
            <td colspan="2" class="val-cell">{{ $dateOfBirth }}</td>
            <td class="label-cell" width="8%">AGE</td>
            <td class="val-cell" width="7%" align="center">{{ $age }}</td>
            <td class="label-cell" width="12%">Contact:</td>
        </tr>

        <!-- Row 4: Address & Contact continuation -->
        <tr>
            <td class="label-cell">ADDRESS</td>
            <td colspan="4" class="val-cell">{{ $address }}</td>
            <td class="val-cell" style="font-size: 8pt;">{{ $contactNo }}</td>
        </tr>

        <!-- Row 5: Schedule Subheader -->
        <tr>
            <td colspan="6" class="section-header-cell">
                TEST SCHEDULE:
            </td>
        </tr>

        <!-- Row 6: Venue -->
        <tr>
            <td class="label-cell">VENUE</td>
            <td colspan="7" class="val-cell" style="color: #064e3b; font-size: 9.5pt;">
                <strong>{{ $venueName }}</strong>
            </td>
        </tr>

        <!-- Row 7: Room & Seat Number -->
        <tr>
            <td class="label-cell">ROOM & SEAT#</td>
            <td colspan="7" class="val-cell">
                {{ $roomName }} &nbsp;&nbsp;|&nbsp;&nbsp; 
                <strong style="color: #047857;">Reserved Seat #{{ $seatNo }}</strong>
            </td>
        </tr>

        <!-- Row 8: Date of Test, Time, Batch -->
        <tr>
            <td class="label-cell">DATE OF TEST</td>
            <td colspan="2" class="val-cell"><strong>{{ $examDateOnly }}</strong></td>
            <td class="label-cell">TIME</td>
            <td class="val-cell"><strong>{{ $examTimeOnly }}</strong></td>
            <td class="label-cell">BATCH</td>
            <td colspan="2" class="val-cell"><strong>{{ $batchName }}</strong></td>
        </tr>

        <!-- Row 9: Signatures -->
        <tr>
            <td class="label-cell" colspan="2">Admission Officer:</td>
            <td colspan="3" class="sig-td">
                <div style="font-weight: bold; font-size: 8.5pt;">{{ $coordinatorName }}</div>
                <div class="sig-caption">Authorized Admission Officer Signature</div>
            </td>
            <td class="label-cell">Applicant Signature:</td>
            <td colspan="2" class="sig-td">
                <div class="sig-line"></div>
                <div class="sig-caption">Signature Over Printed Name</div>
            </td>
        </tr>
    </table>

    <!-- Notes & Guidelines -->
    <div class="notes-area">
        <div style="font-style: italic; color: #6b7280; margin-bottom: 2px;">
            • This is a system-generated print-out and does not require manual countersignature if digitally verified.
        </div>
        <div>
            <strong style="color: #b91c1c;">NOTE:</strong> Late examinees will not be allowed to take the test. Please come <strong>30 minutes before</strong> your scheduled time. If you have any inquiries, please refer to the contact number of your CBSUA Admission Office at <strong>{{ $coordinatorContact }}</strong>.
        </div>
    </div>

    <!-- Official Document Control Footer (ADM-FR-005) -->
    <div class="admin-footer">
        <table>
            <tr>
                <td width="80%">
                    <b>ADM-FR-005</b>
                </td>
                <td align="right">
                    <b>Rev.: 1</b>
                </td>
            </tr>
            <tr>
                <td>
                    Effectivity Date: May 2, 2022
                </td>
                <td align="right">
                    Page 1 of 1
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
