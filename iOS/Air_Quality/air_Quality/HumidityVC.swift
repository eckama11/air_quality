//
//  HumidityVC.swift
//  Air_Quality
//
//  Created by Amanda Eckhardt on 12/9/14.
//  Copyright (c) 2014 Amanda Eckhardt. All rights reserved.
//

import UIKit
import JBChart

class HumidityVC: UIViewController, JBBarChartViewDelegate, JBBarChartViewDataSource {
    
    @IBOutlet var backView: UIView!
    
    @IBOutlet var lineChart: JBBarChartView!
    
    @IBOutlet var informationLabel: UILabel!
    
    var _chartLegend: [String] = [];
    
    func downloadData() {
        // Data
        
        let json = JSON(url:"http://beta.eckama.com/air_quality/iOSHumidity.php")
        if let times = json["time"].asArray {
            var i:Int = 0
            var chartLegend:NSArray
            for time in times {
                var humidity:Double = time["humidity"].asDouble!;
                
            }
            
        }
    }
}
