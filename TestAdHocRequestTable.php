<?php

function testAdHocAbsenceRequestTable() {
    $role = CreateCompanyRole("Cashier", 8);

    $employee = CreateEmployee("Sam Gilbert", "samgilbertuk@hotmail.com", "Zaq12wsx", "1990-11-28", 25, NULL, $role[COMP_ROLE_ID]);

    $absenceType = CreateAbsenceType("Sick Leave", "0", "0");


    //CREATE
    $adHocAbsenceRequest = CreateAdHocAbsenceRequest($employee[EMP_ID], "2014-03-12", "2014-04-12", $absenceType[ABS_TYPE_ID]);

    //RETRIEVE
    $adHocAbsenceRequests = RetrieveAdHocAbsenceRequests();
    $filter[AD_HOC_START] = "2014-03-11";
    $adHocAbsenceRequests = RetrieveAdHocAbsenceRequests($filter);

    if ($adHocAbsenceRequests <> NULL) {
        foreach ($adHocAbsenceRequests as $adHocAbsenceRequest) {
            //UPDATE
            $adHocAbsenceRequest[AD_HOC_START] = "2014-03-11";
            $success = UpdateAdHocAbsenceRequest($adHocAbsenceRequest);

            //DELETE
            $success = DeleteAdHocAbsenceRequest($adHocAbsenceRequest[ABS_TYPE_ID]);
        }
    }
}

?>