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
    @IBOutlet var barChart: JBBarChartView!
    
    @IBOutlet var informationLabel: UILabel!
    
    var time:[String] = [];
    var humidity:[Double] = [];
    
    override func viewDidLoad() {
        super.viewDidLoad()
        informationLabel.text = ""
        let prefs:NSUserDefaults = NSUserDefaults.standardUserDefaults()
        let deviceId:NSString = prefs.objectForKey("deviceId") as NSString
        NSLog("c: %@","c");
        let date:NSString = prefs.objectForKey("date") as NSString
        NSLog("d: %@",date);
        var post:NSString = "deviceId=\(deviceId)&date=\(date)"
        
        NSLog("PostData: %@",date);
        
        var url:NSURL = NSURL(string:"http://beta.eckama.com/air_quality/iOSHumidity.php")!
        
        var postData:NSData = post.dataUsingEncoding(NSASCIIStringEncoding)!
        
        var postLength:NSString = String( postData.length )
        
        var request:NSMutableURLRequest = NSMutableURLRequest(URL: url)
        request.HTTPMethod = "POST"
        request.HTTPBody = postData
        request.setValue(postLength, forHTTPHeaderField: "Content-Length")
        request.setValue("application/x-www-form-urlencoded", forHTTPHeaderField: "Content-Type")
        request.setValue("application/json", forHTTPHeaderField: "Accept")
        
        
        var reponseError: NSError?
        var response: NSURLResponse?
        
        var urlData: NSData? = NSURLConnection.sendSynchronousRequest(request, returningResponse:&response, error:&reponseError)
        
        if ( urlData != nil ) {
            let res = response as NSHTTPURLResponse!;
            
            NSLog("Response code: %ld", res.statusCode);
            
            if (res.statusCode >= 200 && res.statusCode < 300)
            {
                var responseData:NSString  = NSString(data:urlData!, encoding:NSUTF8StringEncoding)!
                
                NSLog("Response ==> %@", responseData);
                
                var parseError: NSError?
                let parsedObject: AnyObject? = NSJSONSerialization.JSONObjectWithData(urlData!, options: NSJSONReadingOptions.AllowFragments,
                    error:&parseError)
                let success = 0
                //here
                
                if let info = parsedObject as? NSDictionary {
                    if let success = info["success"] as? Int {
                        if(success == 1)
                        {
                            
                            NSLog("Response ==> %@", "here0");
                            if let data = info["data"] as? NSArray {
                                NSLog("Response ==> %@", "here1");
                                for dati in data {
                                    NSLog("Response ==> %@", "here2");
                                    time.append(dati["time"] as String!)
                                    NSLog("Response ==> %@", "here3");
                                    humidity.append(dati["humidity"] as Double!)
                                    NSLog("Response ==> %@", "here4");
                                    barChart.backgroundColor = UIColor.blueColor()
                                    NSLog("Response ==> %@", "here5");
                                    barChart.delegate = self
                                    barChart.dataSource = self
                                    barChart.minimumValue = 0
                                    barChart.maximumValue = 100
                                    //barChart.reloadData()
                                    barChart.setState(.Collapsed, animated: false)
                                }
                                //println(time)
                                //println(humidity)
                            }
                        }
                        else {
                            var alertView:UIAlertView = UIAlertView()
                            alertView.title = "Nothing worked!"
                            alertView.message = "didn't work"
                            alertView.delegate = self
                            alertView.addButtonWithTitle("OK")
                            alertView.show()
                        }
                    }
                }
            }
        }
    }
    
    override func viewWillAppear(animated: Bool) {
        super.viewWillAppear(animated)
        
        var footerView = UIView(frame: CGRectMake(0, 0, barChart.frame.width, 16))
        
        println("viewDidLoad: \(barChart.frame.width)")
        
        var footer1 = UILabel(frame: CGRectMake(0, 0, barChart.frame.width/2 - 8, 16))
        footer1.textColor = UIColor.whiteColor()
        footer1.text = "\(time[0])"
        
        var footer2 = UILabel(frame: CGRectMake(barChart.frame.width/2 - 8, 0, barChart.frame.width/2 - 8, 16))
        footer2.textColor = UIColor.whiteColor()
        footer2.text = "\(time[time.count - 1])"
        footer2.textAlignment = NSTextAlignment.Right
        
        footerView.addSubview(footer1)
        footerView.addSubview(footer2)
        
        var header = UILabel(frame: CGRectMake(0, 0, barChart.frame.width, 50))
        header.textColor = UIColor.whiteColor()
        header.font = UIFont.systemFontOfSize(24)
        header.text = "Humidity"
        header.textAlignment = NSTextAlignment.Center
        
        barChart.footerView = footerView
        barChart.headerView = header
    }

    override func viewDidAppear(animated: Bool) {
        super.viewDidAppear(animated)
        
        // our code
        barChart.reloadData()
        
        var timer = NSTimer.scheduledTimerWithTimeInterval(0.5, target: self, selector: Selector("showChart"), userInfo: nil, repeats: false)
    }

    override func viewDidDisappear(animated: Bool) {
        super.viewDidDisappear(animated)
        hideChart()
    }

    func hideChart() {
        barChart.setState(.Collapsed, animated: true)
    }
    
    func showChart() {
        barChart.setState(.Expanded, animated: true)
    }
    
    func numberOfBarsInBarChartView(barChartView: JBBarChartView!) -> UInt {
        return UInt(humidity.count)
    }
    
    func barChartView(barChartView: JBBarChartView!, heightForBarViewAtIndex index: UInt) -> CGFloat {
        return CGFloat(humidity[Int(index)])
    }
    
    func barChartView(barChartView: JBBarChartView!, colorForBarViewAtIndex index: UInt) -> UIColor! {
        return (index % 2 == 0) ? UIColor.lightGrayColor() : UIColor.whiteColor()
    }
    
    func barChartView(barChartView: JBBarChartView!, didSelectBarAtIndex index: UInt) {
        let data = humidity[Int(index)]
        let key = time[Int(index)]
        
        informationLabel.text = "Humidity at \(key): \(data)"
    }
    
    func didDeselectBarChartView(barChartView: JBBarChartView!) {
        informationLabel.text = ""
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
    }
    
}
