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
    
    @IBOutlet var lineChart: JBLineChartView!
    
    @IBOutlet var informationLabel: UILabel!
    
    func downloadData() {
        // Data
        var results = [];
        var dateFormatter = NSDateFormatter();
        dateFormatter.dateFormat = "MM-dd";
        
        let json = JSON(url:"http://api.openweathermap.org/data/2.5/forecast/daily?q=atlanta&mode=json&units=metric&cnt=5")
        if let days = json["days"].asArray {
            var i:Int = 0 ;
            for day in days {
            }
            
            _lineChartView.reloadData()
            _lineChartView.setState(JBChartViewState.Expanded, animated: true)
            
        }
    }
}
