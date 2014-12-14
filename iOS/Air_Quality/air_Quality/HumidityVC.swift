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
    
    override func viewDidLoad() {
        super.viewDidLoad()
        let d = backView.frame.size.height
        // Do any additional setup after loading the view, typically from a nib.
        
        var label = UILabel(frame: CGRectMake(d, d, 200, 21))
        label.center = CGPointMake(160, 284)
        label.textAlignment = NSTextAlignment.Center
        label.text = "I'm a test label"
        self.view.addSubview(label)
        
        let barChartView = JBBarChartView();
        barChartView.dataSource = self;
        barChartView.delegate = self;
        barChartView.backgroundColor = UIColor.whiteColor();
        barChartView.frame = CGRectMake(0, 1 + d, 320, 200);
        barChartView.reloadData();
        self.view.addSubview(barChartView);
        
        println("Launched");
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    func numberOfBarsInBarChartView(barChartView: JBBarChartView!) -> UInt {
        println("numberOfBarsInBarChartView");
        return 10 //number of lines in chart
    }
    
    func barChartView(barChartView: JBBarChartView, heightForBarViewAtIndex index: UInt) -> CGFloat {
        println("barChartView", index);
        
        return CGFloat(arc4random_uniform(100));
    }
}
