//
//  ViewController.swift
//  Air_Quality
//
//  Created by Amanda Eckhardt on 12/3/14.
//  Copyright (c) 2014 Amanda Eckhardt. All rights reserved.
//

import UIKit

class ViewController: UIViewController {
    
    @IBOutlet var usernameLabel: UILabel!
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    override func viewDidAppear(animated: Bool) {
        super.viewDidAppear(true)
        
        let prefs:NSUserDefaults = NSUserDefaults.standardUserDefaults()
        let isLoggedIn:Int = prefs.integerForKey("ISLOGGEDIN") as Int
        if (isLoggedIn != 1) {
            self.performSegueWithIdentifier("goto_login", sender: self)
        } else {
            self.usernameLabel.text = prefs.objectForKey("USERNAME") as NSString
        }
    }
    
    @IBAction func logoutTapped(sender: UIButton) {
        self.performSegueWithIdentifier("goto_login", sender: self)
    }

}

