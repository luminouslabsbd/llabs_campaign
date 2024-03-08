<?php

namespace Luminouslabs\Installer\Service;

class ApplePassService
{

    /*public function applePass($data)
    {
        $userId = auth('partner')->user()->keos_passkit_id;
        if ($data->waletType == 0){
            $passTypeData = [
                "passDetails" => [
                    "logoImage" => $data->logoVal ?? "",
                    "heroImage" => $data->heroImageVal ?? "",
                    "color" => $data->backgroundColorVal ?? "rgb(24, 97, 134)",
                    "labelColor" => $data->labelColorVal ?? "rgb(255, 255, 255)",
                    "formate" => "QR_CODE",
                    "barcodeValue" => "",
                    "passTypeIdentifier" => "",
                    "passId" => null,
                    "header" => "Keos",
                    "cardTitle" => $data->cardNameVal ?? "Card Title",
                    "activeCardName" => "StoreCard"
                ],
                "textModulesData" => [
                    "firstRowData" => [
                        [
                            "id" => 16,
                            "label" => [
                                "label" => "Label",
                                "value" => $data->firstRowFirstElementLabel ?? "Points1",
                                "baseValue" =>"Points"
                            ],
                            "displayValue" => [
                                "label" => "Display Value",
                                "value" => $data->firstRowFirstElementVal ?? "123123"
                            ]
                        ],
                        [
                            "id" => 4,
                            "expirySettings" => [
                                "label" => "Expiry Settings",
                                "value" => "expires"
                            ],
                            "displayValue" => [
                                "label" => "Date Format",
                                "value" => ""
                            ],
                            "label" => [
                                "label" => "Label",
                                "value" => "Expiry Date1",
                                "baseValue" => "Expiry Date"
                            ]
                        ],
                        [
                            "id" => 7,
                            "label" => [
                                "label" => "Label",
                                "value" => "Gender",
                                "baseValue" => $data->firstRowThirdElementLabel ?? "Gender"
                            ],
                            "displayValue" => [
                                "label" => "Display Value",
                                "value" => $data->firstRowThirdElementVal ?? "Gender"
                            ]
                        ]
                    ],
                    "secondRowData" => [
                        [
                            "id" => 6,
                            "label" => [
                                "label" => "Label",
                                "value" => $data->secondRowFirstElementLabel ?? "First Name2",
                                "baseValue" => "First Name"
                            ],
                            "displayValue" => [
                                "label" => "Display Value",
                                "value" => $data->secondRowFirstElementVal ?? "Luminous"
                            ]
                        ],
                        [
                            "id" => 9,
                            "label" => [
                                "label" => "Label",
                                "value" => $data->secondRowSecondElementLabel ?? "Last Name2",
                                "baseValue" => "Last Name"
                            ],
                            "displayValue" => [
                                "label" => "Display Value",
                                "value" => $data->secondRowSecondElementVal ?? "N/A"
                            ]
                        ],
                        [
                            "id" => 14,
                            "label" => [
                                "label" => "Label",
                                "value" => $data->secondRowThirdElementLabel ?? "Name",
                                "baseValue" => "Name"
                            ],
                            "displayValue" => [
                                "label" => "Display Value",
                                "value" => $data->secondRowThirdElementVal ?? "LuminL"
                            ]
                        ]
                    ],
                    "thirdRowData" => [
                        [
                            "id" => 13,
                            "label" => [
                                "label" => "Label",
                                "value" => $data->thirdRowFirstElementLabel ?? "Mobile Number3",
                                "baseValue" => "Mobile Number"
                            ],
                            "displayValue" => [
                                "label" => "Display Value",
                                "value" => $data->thirdRowFirstElementVal ?? "N/A"
                            ]
                        ],
                        [
                            "id" => 3,
                            "label" => [
                                "label" => "Label",
                                "value" => $data->thirdRowSecondlementLabel ?? "Email3",
                                "baseValue" => "Email"
                            ],
                            "displayValue" => [
                                "label" => "Display Value",
                                "value" => $data->thirdRowSecondlementVal ?? "email@gmail.com"
                            ]
                        ],
                        [
                            "id" => 1,
                            "fieldType" => [
                                "label" => "Field Type",
                                "value" => $data->thirdRowThirdElementLabel ?? "Date YYYYMMDD"
                            ],
                            "dateFormat" => [
                                "label" => "Date Format",
                                "value" => $data->thirdRowThirdElementVal ?? "No Format"
                            ],
                            "label" => [
                                "label" => "Date of Birth",
                                "value" => $data->thirdRowThirdElementLabel ?? "Date of Birth3",
                                "baseValue" => "Date of Birth"
                            ],
                            "displayValue" => [
                                "label" => "Display Value",
                                "value" => $data->thirdRowThirdElementVal ?? "N/A"
                            ]
                        ]
                    ],
                    "fourthRowData" => [
                        [
                            "id" => 12,
                            "label" => [
                                "label" => "Label",
                                "value" => $data->fourthRowFirstElementLabel ?? "Member Status4",
                                "baseValue" => "Member Status"
                            ],
                            "displayValue" => [
                                "label" => "Display Value",
                                "value" => $data->fourthRowFirstElementVal ?? "active"
                            ]
                        ],
                        [
                            "id" => 10,
                            "label" => [
                                "label" => "Label",
                                "value" => $data->fourthRowSecondElementLabel ?? "Legal4",
                                "baseValue" => "Legal"
                            ],
                            "displayValue" => [
                                "label" => "Display Value",
                                "value" => $data->fourthRowSecondElementVal ?? "valid"
                            ]
                        ],
                        [
                            "id" => 22,
                            "tierName" => [
                                "label" => "Tier Name",
                                "value" => $data->fourthRowThirdElementLabel ?? "Bronze"
                            ],
                            "label" => [
                                "label" => "Label",
                                "value" => $data->fourthRowThirdElementLabel ?? "Tier4",
                                "baseValue" => "Tier"
                            ],
                            "displayValue" => [
                                "label" => "Display Value",
                                "value" => $data->fourthRowThirdElementVal ?? "56356"
                            ]
                        ]
                    ]
                ],
                "userId" => $userId,
                "id" => "3388000000022308850",
                "classId" => "3388000000022308850"
            ];
        }else if($data->waletType == 1){
            if ($data->cardType == 1){
                $passTypeData = [
                    "GenericPass" => [
                        "secondaryFormsData" => [
                            [
                                "id" => 9,
                                "label" => [
                                    "label" => "Label",
                                    "value" => $data->secondRowFirstElementLabel ?? "Last Name",
                                    "baseValue" => "Last Name"
                                ],
                                "displayValue" => [
                                    "label" => "Display Value",
                                    "value" => $data->secondRowFirstElementVal ?? "L. Name"
                                ]
                            ],
                            [
                                "id" => 13,
                                "label" => [
                                    "label" => "Label",
                                    "value" => $data->secondRowSecondElementLabel ?? "Mobile Number",
                                    "baseValue" => "Mobile Number"
                                ],
                                "displayValue" => [
                                    "label" => "Display Value",
                                    "value" => $data->secondRowSecondElementVal ?? "093852345"
                                ]
                            ],
                            [
                                "id" => 14,
                                "label" => [
                                    "label" => "Label",
                                    "value" => $data->secondRowThirdElementLabel ?? "Name",
                                    "baseValue" => "Name"
                                ],
                                "displayValue" => [
                                    "label" => "Display Value",
                                    "value" => $data->secondRowThirdElementVal ?? "putar1"
                                ]
                            ]
                        ],
                        "userId" => $userId,
                        "passDetails" => [
                            "logoImage" => $data->logoVal ?? "",
                            "heroImage" => $data->heroImageVal ?? "",
                            "color" =>  hexeToRgb($data->backgroundColorVal) ?? "rgb(190, 184, 35)",
                            "labelColor" => hexeToRgb($data->labelColorVal) ?? "rgb(255, 255, 255)",
                            "formate" => "PKBarcodeFormatQR",
                            "barcodeValue" => "",
                            "passTypeIdentifier" => "",
                            "passId" => "",
                            "header" => "My Organization",
                            "cardTitle" => $data->cardNameVal ?? "My Loyalty Card"
                        ],
                        "headerFields" => [
                            "points" => [
                                "id" => 16,
                                "label" => [
                                    "label" => "Label",
                                    "value" => "PointsA",
                                    "baseValue" => "Points"
                                ],
                                "displayValue" => [
                                    "label" => "Display Value",
                                    "value" => "23423"
                                ]
                            ]
                        ],
                        "backFields" => [],
                        "primaryFormsData" => [
                            "id" => 6,
                            "label" => [
                                "label" => "Label",
                                "value" => $data->firstRowFirstElementLabel ?? "First Name",
                                "baseValue" => "First Name"
                            ],
                            "displayValue" => [
                                "label" => "Display Value",
                                "value" => $data->firstRowFirstElementVal ??  "F Name"
                            ]
                        ],
                        "auxiliaryFormsData" => [
                            [
                                "id" => 22,
                                "tierName" => [
                                    "label" => "Tier Name",
                                    "value" => "Bronze"
                                ],
                                "label" => [
                                    "label" => "Label",
                                    "value" => $data->thirdRowFirstElementLabel ?? "Tiera",
                                    "baseValue" => "Tier"
                                ],
                                "displayValue" => [
                                    "label" => "Display Value",
                                    "value" => $data->thirdRowFirstElementVal ?? "6743AD"
                                ]
                            ],
                            [
                                "id" => 3,
                                "label" => [
                                    "label" => "Label",
                                    "value" => $data->thirdRowSecondElementLabel ?? "Email",
                                    "baseValue" => "Email"
                                ],
                                "displayValue" => [
                                    "label" => "Display Value",
                                    "value" => $data->thirdRowSecondElementVal ?? "email@gmail.com"
                                ]
                            ],
                            [
                                "id" => 10,
                                "label" => [
                                    "label" => "Label",
                                    "value" => $data->thirdRowThirdElementLabel ?? "Legal",
                                    "baseValue" => "Legal"
                                ],
                                "displayValue" => [
                                    "label" => "Display Value",
                                    "value" => $data->thirdRowThirdElementVal ?? "Valid"
                                ]
                            ]
                        ]
                    ]
                ];
            }elseif ($data->cardType == 0 || $data->cardType == 2){
                $passTypeData = [
                    "Coupon" => [
                        "secondaryFormsData" => [
                            [
                                "id" => 6,
                                "label" => [
                                    "label" => "Label",
                                    "value" => $data->firstRowFirstElementLabel ?? "First Name",
                                    "baseValue" => "First Name"
                                ],
                                "displayValue" => [
                                    "label" => "Display Value",
                                    "value" => $data->firstRowFirstElementVal ?? "putar1"
                                ]
                            ],
                            [
                                "id" => 9,
                                "label" => [
                                    "label" => "Label",
                                    "value" => $data->firstRowSecondElementLabel ?? "Last Name",
                                    "baseValue" => "Last Name"
                                ],
                                "displayValue" => [
                                    "label" => "Display Value",
                                    "value" => $data->firstRowSecondElementVal ?? "purta2"
                                ]
                            ],
                            [
                                "id" => 13,
                                "label" => [
                                    "label" => "Label",
                                    "value" => $data->firstRowThirdElementLabel ?? "Mobile Number",
                                    "baseValue" => "Mobile Number"
                                ],
                                "displayValue" => [
                                    "label" => "Display Value",
                                    "value" => $data->firstRowThirdElementVal ?? "9083452345234"
                                ]
                            ]
                        ],
                        "userId" => $userId,
                        "passDetails" => [
                            "logoImage" => $data->logoVal ??  "",
                            "heroImage" => $data->heroImageVal ?? "",
                            "color" => hexeToRgb($data->backgroundColorVal) ?? "rgb(47, 15, 15)",
                            "labelColor" => hexeToRgb($data->labelColorVal)?? "rgb(255, 255, 255)",
                            "formate" => "PKBarcodeFormatQR",
                            "barcodeValue" => "",
                            "passTypeIdentifier" => "",
                            "passId" => "",
                            "header" => "My Organization",
                            "cardTitle" => $data->cardNameVal ?? "My Loyalty Card"
                        ],
                        "headerFields" => [
                            "expiryDate" => [
                                "id" => 4,
                                "expirySettings" => [
                                    "label" => "Expiry Settings",
                                    "value" => "expires"
                                ],
                                "displayValue" => [
                                    "label" => "Date Format",
                                    "value" => "2024-03-14T10:00:46+06:00"
                                ],
                                "label" => [
                                    "label" => "Label",
                                    "value" => "Expiry Date",
                                    "baseValue" => "Expiry Date"
                                ]
                            ]
                        ],
                        "backFields" => [],
                        "primaryFormsData" => [],
                        "auxiliaryFormsData" => []
                    ]
                ];
            }else{
                return "Invalide Card Type";
            }
        }else{
            return "Invalide Pass Type";
        }
        return $passTypeData;
    }*/

    public function getFormate($fromData)
    {
        //return $fromData;

        $userId = auth('partner')->user()->keos_passkit_id;

        if ($fromData->cardType == 0){
            $cardName = "StoreCard";
        }elseif ($fromData->cardType == 1){
            $cardName = "GenericPass";
        }elseif ($fromData->cardType == 2){
            $cardName = "Coupon";
        }

        if ($fromData->waletType == 0){
            $data = [
                "passDetails" => [
                    "logoImage" => $fromData->uploadedLogo ?? "",
                    "heroImage" => $fromData->upladedHeroImg ??  "",
                    "color" => hexeToRgb($fromData->backgroundColorVal) ?? "rgb(194, 0, 0)",
                    "labelColor" => hexeToRgb($fromData->labelColorVal) ?? "rgb(247, 247, 247)",
                    "formate" => $fromData->barcodeFormat ?? "QR_CODE",
                    "barcodeValue" => $fromData->barcodeValue ?? "",
                    "passTypeIdentifier" => "",
                    "passId" => "",
                    "header" => "My Organization",
                    "cardTitle" => "My Loyalty Card",
                    "activeCardName" => $cardName ?? "StoreCard"
                ],
                "textModulesData" => [
                    "firstRowData" => [
                        [
                            "id" => 16,
                            "label" => [
                                "label" => "Label",
                                "value" => $fromData->firstRowFirstElementLabel ?? "Points",
                                "baseValue" => "Points"
                            ],
                            "displayValue" => [
                                "label" => "Display Value",
                                "value" => $fromData->firstRowFirstElementVal ?? "1000"
                            ]
                        ],
                        [
                            "id" => 4,
                            "expirySettings" => [
                                "label" => "Expiry Settings",
                                "value" => "Not Expired"
                            ],
                            "displayValue" => [
                                "label" => "Date Format",
                                "value" => "2024-03-31T07:40:13+06:00"
                            ],
                            "label" => [
                                "label" => "Label",
                                "value" => $fromData->firstRowSecondElementLabel ?? "Expiry Dateas",
                                "baseValue" => "Expiry Date"
                            ]
                        ],
                        [
                            "id" => 3,
                            "label" => [
                                "label" => "Label",
                                "value" => $fromData->firstRowThirdElementLabel ?? "Email",
                                "baseValue" => "Email"
                            ],
                            "displayValue" => [
                                "label" => "Display Value",
                                "value" => $fromData->firstRowThirdElementVal ?? "example@gmail.com"
                            ]
                        ]
                    ],
                    "secondRowData" => [
                        [
                            "id" => 6,
                            "label" => [
                                "label" => "Label",
                                "value" => $fromData->secondRowFirstElementLabel ?? "First Name",
                                "baseValue" => "First Name"
                            ],
                            "displayValue" => [
                                "label" => "Display Value",
                                "value" => $fromData->secondRowFirstElementVal ?? "First Name"
                            ]
                        ],
                        [
                            "id" => 9,
                            "label" => [
                                "label" => "Label",
                                "value" => $fromData->secondRowSecondElementLabel ?? "Last Name",
                                "baseValue" => "Last Name"
                            ],
                            "displayValue" => [
                                "label" => "Display Value",
                                "value" => $fromData->secondRowSecondElementVal ?? "Last Name"
                            ]
                        ],
                        [
                            "id" => 13,
                            "label" => [
                                "label" => "Label",
                                "value" => $fromData->secondRowThirdElementLabel ?? "Mobile Number",
                                "baseValue" => "Mobile Number"
                            ],
                            "displayValue" => [
                                "label" => "Display Value",
                                "value" => $fromData->secondRowThirdElementVal ?? "98374873"
                            ]
                        ]
                    ],
                    "thirdRowData" => [
                        [
                            "id" => 27,
                            "label" => [
                                "label" => "Label",
                                "value" => $fromData->thirdRowFirstElementLabel ?? "Opt in",
                                "baseValue" => "Opt in"
                            ],
                            "displayValue" => [
                                "label" => "Display Value",
                                "value" => $fromData->thirdRowFirstElementVal ?? "Opt in"
                            ]
                        ],
                        [
                            "id" => 15,
                            "label" => [
                                "label" => "Label",
                                "value" => $fromData->thirdRowSecondlementLabel ?? "Opt out",
                                "baseValue" => "Opt out"
                            ],
                            "displayValue" => [
                                "label" => "Display Value",
                                "value" => $fromData->thirdRowSecondlementVal ?? "Opt out"
                            ]
                        ],
                        [
                            "id" => 23,
                            "label" => [
                                "label" => "Label",
                                "value" => $fromData->thirdRowThirdElementLabel ?? "Tier Points",
                                "baseValue" => "Tier points"
                            ],
                            "displayValue" => [
                                "label" => "Display Value",
                                "value" => $fromData->thirdRowThirdElementVal ?? "155"
                            ]
                        ]
                    ],
                    "fourthRowData" => [
                        [
                            "id" => 10,
                            "label" => [
                                "label" => "Label",
                                "value" => $fromData->fourthRowFirstElementLabel ?? "Legal",
                                "baseValue" => "Legal"
                            ],
                            "displayValue" => [
                                "label" => "Display Value",
                                "value" => $fromData->fourthRowFirstElementVal ?? "Legal"
                            ]
                        ],
                        [
                            "id" => 22,
                            "tierName" => [
                                "label" => "Tier Name",
                                "value" => "Bronze"
                            ],
                            "label" => [
                                "label" => "Label",
                                "value" => $fromData->fourthRowSecondElementLabel ?? "Tier",
                                "baseValue" => "Tier"
                            ],
                            "displayValue" => [
                                "label" => "Display Value",
                                "value" => $fromData->fourthRowSecondElementVal ?? "Tier"
                            ]
                        ],
                        [
                            "id" => 19,
                            "label" => [
                                "label" => "Label",
                                "value" => $fromData->fourthRowThirdElementLabel ?? "Secondary Points",
                                "baseValue" => "Secondary Points"
                            ],
                            "displayValue" => [
                                "label" => "Display Value",
                                "value" => $fromData->fourthRowThirdElementVal ?? "122"
                            ]
                        ]
                    ]
                ],
                "userId" => $userId,
                "id" => "3388000000022308850",
                "classId" => "3388000000022308850"
            ];
        }elseif ($fromData->waletType == 1){
            if ($cardName == "StoreCard" || $cardName == "Coupon"){
                $data = [
                    "Coupon" => [
                        "secondaryFormsData" => [
                            [
                                "id" => 6,
                                "label" => [
                                    "label" => "Label",
                                    "value" => $fromData->firstRowFirstElementLabel ?? "First Name",
                                    "baseValue" => "First Name"
                                ],
                                "displayValue" => [
                                    "label" => "Display Value",
                                    "value" => $fromData->firstRowFirstElementVal ?? "putar1"
                                ]
                            ],
                            [
                                "id" => 9,
                                "label" => [
                                    "label" => "Label",
                                    "value" => $fromData->firstRowSecondElementLabel ?? "Last Name",
                                    "baseValue" => "Last Name"
                                ],
                                "displayValue" => [
                                    "label" => "Display Value",
                                    "value" => $fromData->firstRowSecondElementVal ?? "purta2"
                                ]
                            ],
                            [
                                "id" => 13,
                                "label" => [
                                    "label" => "Label",
                                    "value" => $fromData->firstRowThirdElementLabel ?? "Mobile Number",
                                    "baseValue" => "Mobile Number"
                                ],
                                "displayValue" => [
                                    "label" => "Display Value",
                                    "value" => $fromData->firstRowThirdElementVal ?? "9083452345234"
                                ]
                            ]
                        ],
                        "userId" => $userId,
                        "passDetails" => [
                            "logoImage" => $fromData->uploadedLogo ??  "",
                            "heroImage" => $fromData->upladedHeroImg ?? "",
                            "color" => hexeToRgb($fromData->backgroundColorVal) ?? "rgb(47, 15, 15)",
                            "labelColor" => hexeToRgb($fromData->labelColorVal) ?? "rgb(255, 255, 255)",
                            "formate" => $fromData->barcodeFormat ?? "PKBarcodeFormatQR",
                            "barcodeValue" => $fromData->barcodeValue ?? "",
                            "passTypeIdentifier" => "",
                            "passId" => "",
                            "header" => "My Organization",
                            "cardTitle" => $fromData->cardNameVal ?? "My Loyalty Card"
                        ],
                        "headerFields" => [
                            "expiryDate" => [
                                "id" => 4,
                                "expirySettings" => [
                                    "label" => "Expiry Settings",
                                    "value" => "expires"
                                ],
                                "displayValue" => [
                                    "label" => "Date Format",
                                    "value" => "2024-03-14T10:00:46+06:00"
                                ],
                                "label" => [
                                    "label" => "Label",
                                    "value" => "Expiry Date",
                                    "baseValue" => "Expiry Date"
                                ]
                            ]
                        ],
                        "backFields" => [],
                        "primaryFormsData" => [],
                        "auxiliaryFormsData" => []
                    ]
                ];
            }elseif($cardName == "GenericPass"){
                $data = [
                    "GenericPass" => [
                        "secondaryFormsData" => [
                            [
                                "id" => 6,
                                "label" => [
                                    "label" => "Label",
                                    "value" => $fromData->secondRowFirstElementLabel ?? "First Name",
                                    "baseValue" => "First Name"
                                ],
                                "displayValue" => [
                                    "label" => "Display Value",
                                    "value" => $fromData->secondRowFirstElementVal ?? "First Name"
                                ]
                            ],
                            [
                                "id" => 9,
                                "label" => [
                                    "label" => "Label",
                                    "value" => $fromData->secondRowSecondElementLabel ?? "Last Name",
                                    "baseValue" => "Last Name"
                                ],
                                "displayValue" => [
                                    "label" => "Display Value",
                                    "value" => $fromData->secondRowSecondElementVal ?? "Last Name"
                                ]
                            ],
                            [
                                "id" => 3,
                                "label" => [
                                    "label" => "Label",
                                    "value" => $fromData->secondRowThirdElementLabel ?? "Email",
                                    "baseValue" => "Email"
                                ],
                                "displayValue" => [
                                    "label" => $fromData->secondRowThirdElementVal ?? "Display Value",
                                    "value" => "email@gmail.com"
                                ]
                            ]
                        ],
                        "userId" => $userId,
                        "passDetails" => [
                            "logoImage" => $fromData->uploadedLogo ?? "",
                            "heroImage" =>  $fromData->upladedHeroImg ?? "",
                            "color" => hexeToRgb($fromData->backgroundColorVal)  ?? "rgb(30, 49, 129)",
                            "labelColor" => hexeToRgb($fromData->labelColorVal) ?? "rgb(255, 255, 255)",
                            "formate" => $fromData->barcodeFormat ?? "PKBarcodeFormatQR",
                            "barcodeValue" => $fromData->barcodeValue ?? "",
                            "passTypeIdentifier" => "",
                            "passId" => "",
                            "header" => "My Organization",
                            "cardTitle" => "My Loyalty Card"
                        ],
                        "headerFields" => [
                            "points" => [
                                "label" => [
                                    "label" => "Label",
                                    "value" => "Points"
                                ],
                                "displayValue" => [
                                    "label" => "Display Value",
                                    "value" => "100"
                                ]
                            ]
                        ],
                        "backFields" => [],
                        "primaryFormsData" => [
                            "id" => 14,
                            "label" => [
                                "label" => "Label",
                                "value" => "Name",
                                "baseValue" => "Name"
                            ],
                            "displayValue" => [
                                "label" => "Display Value",
                                "value" => "Apple Name"
                            ]
                        ],
                        "auxiliaryFormsData" => [
                            [
                                "id" => 27,
                                "label" => [
                                    "label" => "Label",
                                    "value" => $fromData->thirdRowFirstElementLabel ?? "Opt in",
                                    "baseValue" => "Opt in"
                                ],
                                "displayValue" => [
                                    "label" => "Display Value",
                                    "value" => $fromData->thirdRowFirstElementVal ?? "Opt in"
                                ]
                            ],
                            [
                                "id" => 15,
                                "label" => [
                                    "label" => "Label",
                                    "value" => $fromData->thirdRowSecondElementLabel ?? "Opt out",
                                    "baseValue" => "Opt out"
                                ],
                                "displayValue" => [
                                    "label" => "Display Value",
                                    "value" => $fromData->thirdRowSecondElementVal ?? "Opt out"
                                ]
                            ],
                            [
                                "id" => 22,
                                "tierName" => [
                                    "label" => "Tier Name",
                                    "value" => "Bronze"
                                ],
                                "label" => [
                                    "label" => "Label",
                                    "value" => $fromData->thirdRowThirdElementLabel ?? "Tier",
                                    "baseValue" => "Tier"
                                ],
                                "displayValue" => [
                                    "label" => "Display Value",
                                    "value" => $fromData->thirdRowThirdElementVal ?? "Tier"
                                ]
                            ]
                        ]
                    ]
                ];
            }
        }

        return $data;
    }
}
