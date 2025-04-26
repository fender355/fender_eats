<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fender Eats Cookie Preferences</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 2rem; }
        .container { max-width: 800px; margin: 0 auto; }
        h1 { color: #2c3e50; border-bottom: 2px solid #ecf0f1; padding-bottom: 0.5rem; }
        h2 { color: #34495e; margin-top: 1.5rem; }
        .cookie-controls { background: #f5f5f5; padding: 1.5rem; border-radius: 4px; }
        .cookie-type { margin-bottom: 1rem; padding: 1rem; background: white; border-radius: 4px; }
        .btn { background: #27ae60; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Fender Eats Cookie Preferences</h1>
        <p><em>Last Updated: <?php echo date("F j, Y"); ?></em></p>

        <h2>About Cookies</h2>
        <p>Cookies are small text files stored on your device when you use our website or app. They help us provide a better, more personalized experience.</p>

        <div class="cookie-controls">
            <h2>Manage Your Cookie Preferences</h2>
            <form id="cookieForm">
                <div class="cookie-type">
                    <h3>Essential Cookies</h3>
                    <p><input type="checkbox" id="essential" checked disabled>
                    <label for="essential">Required for basic functionality</label></p>
                    <p>These cookies are necessary for the website to function and cannot be switched off. They include security features and basic preferences.</p>
                </div>

                <div class="cookie-type">
                    <h3>Performance Cookies</h3>
                    <p><input type="checkbox" id="performance" checked>
                    <label for="performance">Allow performance cookies</label></p>
                    <p>These help us understand how visitors interact with our website by collecting anonymous information. Disabling may affect our ability to improve services.</p>
                </div>

                <div class="cookie-type">
                    <h3>Functional Cookies</h3>
                    <p><input type="checkbox" id="functional" checked>
                    <label for="functional">Allow functional cookies</label></p>
                    <p>These enable enhanced functionality like remembering preferences. The website may not function properly without them.</p>
                </div>

                <div class="cookie-type">
                    <h3>Advertising Cookies</h3>
                    <p><input type="checkbox" id="advertising">
                    <label for="advertising">Allow advertising cookies</label></p>
                    <p>These track browsing habits to show personalized ads. They may be set by third parties.</p>
                </div>

                <button type="submit" class="btn">Save Preferences</button>
                <button type="button" id="rejectAll" class="btn" style="background: #e74c3c;">Reject All Optional</button>
                <button type="button" id="acceptAll" class="btn">Accept All</button>
            </form>
        </div>

        <h2>Detailed Cookie Information</h2>
        <table style="width:100%; border-collapse: collapse;">
            <tr style="background: #ecf0f1;">
                <th style="padding: 0.5rem; text-align: left;">Cookie Name</th>
                <th style="padding: 0.5rem; text-align: left;">Purpose</th>
                <th style="padding: 0.5rem; text-align: left;">Duration</th>
            </tr>
            <tr style="border-bottom: 1px solid #ddd;">
                <td style="padding: 0.5rem;">session_id</td>
                <td style="padding: 0.5rem;">Maintains your session</td>
                <td style="padding: 0.5rem;">Session</td>
            </tr>
            <tr style="border-bottom: 1px solid #ddd;">
                <td style="padding: 0.5rem;">preferences</td>
                <td style="padding: 0.5rem;">Stores your settings</td>
                <td style="padding: 0.5rem;">1 year</td>
            </tr>
            <tr style="border-bottom: 1px solid #ddd;">
                <td style="padding: 0.5rem;">_ga</td>
                <td style="padding: 0.5rem;">Google Analytics</td>
                <td style="padding: 0.5rem;">2 years</td>
            </tr>
        </table>

        <h2>Browser Controls</h2>
        <p>You can manage cookies through your browser settings:</p>
        <ul>
            <li><a href="https://support.google.com/chrome/answer/95647" target="_blank">Chrome</a></li>
            <li><a href="https://support.mozilla.org/en-US/kb/enhanced-tracking-protection-firefox-desktop" target="_blank">Firefox</a></li>
            <li><a href="https://support.apple.com/guide/safari/manage-cookies-and-website-data-sfri11471/mac" target="_blank">Safari</a></li>
            <li><a href="https://support.microsoft.com/en-us/microsoft-edge/delete-cookies-in-microsoft-edge-63947406-40ac-c3b8-57b9-2a946a29ae09" target="_blank">Edge</a></li>
        </ul>

        <h2>Contact Us</h2>
        <p>For questions about our cookie practices:<br>
        Fender Eats Data Protection Team<br>
        Vosdvizhenka str. 1<br>
        Moscow 121019<br>
        Russia<br>
        Email: <a href="mailto:cookies@fendereats.com">fender355@icloud.com</a></p>
        
        <div>
            <p><strong>© 2025 Perniagaan Fender Maju Jaya. All rights reserved.</strong></p>
        </div>
    </div>

    <script>
        document.getElementById('cookieForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Your cookie preferences have been saved.');
        });
        
        document.getElementById('rejectAll').addEventListener('click', function() {
            document.getElementById('performance').checked = false;
            document.getElementById('functional').checked = false;
            document.getElementById('advertising').checked = false;
            alert('All optional cookies have been rejected.');
        });
        
        document.getElementById('acceptAll').addEventListener('click', function() {
            document.getElementById('performance').checked = true;
            document.getElementById('functional').checked = true;
            document.getElementById('advertising').checked = true;
            alert('All cookies have been accepted.');
        });
    </script>
</body>
</html>