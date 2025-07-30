        // --- Stream Availability Check on Load ---
        async function checkStreamAvailability(url) {
            logMessage(`स्ट्रीम URL की उपलब्धता की जांच कर रहा है: ${url}`, 'info');
            try {
                const response = await fetch(url, { method: 'HEAD', mode: 'no-cors' }); // Use HEAD to avoid downloading full stream
                logMessage(`स्ट्रीम URL स्थिति कोड: ${response.status}`, 'info');

                if (response.status >= 200 && response.status < 300) {
                    // 2xx status codes indicate success
                    logMessage('स्ट्रीम URL उपलब्ध है।', 'info');
                    return { success: true };
                } else if (response.status === 0) {
                    // Status 0 often means network error or CORS issue when using no-cors mode
                    logMessage('स्ट्रीम URL तक पहुंचने में नेटवर्क या CORS त्रुटि।', 'error');
                    return {
                        success: false,
                        code: 'NETWORK_OR_CORS_ERROR',
                        message: 'स्ट्रीम URL तक पहुंचने में नेटवर्क या CORS त्रुटि। यह URL अनुपलब्ध हो सकता है, या सुरक्षा प्रतिबंधों के कारण एक्सेस नहीं किया जा सकता है।',
                        troubleshoot: [
                            'सुनिश्चित करें कि आपका इंटरनेट कनेक्शन स्थिर है।',
                            'जांचें कि स्ट्रीम URL सही है और सक्रिय है।',
                            'यदि URL HTTP पर है, तो आपका ब्राउज़र HTTPS पेज से इसे ब्लॉक कर सकता है।',
                            'यह CORS (क्रॉस-ओरिजिन रिसोर्स शेयरिंग) नीति का मुद्दा हो सकता है।'
                        ]
                    };
                } else {
                    // Other non-2xx status codes
                    logMessage(`स्ट्रीम URL त्रुटि: ${response.status} ${response.statusText}`, 'error');
                    return {
                        success: false,
                        code: response.status,
                        message: `स्ट्रीम सर्वर से त्रुटि प्रतिक्रिया मिली: ${response.status} ${response.statusText || 'अज्ञात त्रुटि'}`,
                        troubleshoot: [
                            'सुनिश्चित करें कि स्ट्रीम URL सही है।',
                            'स्ट्रीम सर्वर वर्तमान में डाउन हो सकता है।',
                            'बाद में पुनः प्रयास करें।'
                        ]
                    };
                }
            } catch (error) {
                logMessage(`स्ट्रीम URL की जांच करते समय त्रुटि: ${error.message}`, 'error');
                return {
                    success: false,
                    code: 'FETCH_ERROR',
                    message: `स्ट्रीम URL की जांच करते समय एक त्रुटि हुई: ${error.message}`,
                    troubleshoot: [
                        'सुनिश्चित करें कि आपका इंटरनेट कनेक्शन स्थिर है।',
                        'जांचें कि स्ट्रीम URL सही है और सक्रिय है।',
                        'यह एक नेटवर्क समस्या हो सकती है।',
                        'यदि URL HTTPS नहीं है, तो आपका ब्राउज़र सुरक्षा कारणों से उसे ब्लॉक कर सकता है।'
                    ]
                };
            }
        }
// load jsx

// Assuming logMessage function is defined elsewhere
// function logMessage(message, type) { console.log(`[${type.toUpperCase()}] ${message}`); }

// --- Stream Availability Check on Load ---
async function checkStreamAvailability(url, { enforceCors = false } = {}) {
    logMessage(`स्ट्रीम URL की उपलब्धता की जांच कर रहा है: ${url} (CORS enforce: ${enforceCors})`, 'info');

    if (enforceCors) {
        // Attempt a fetch with CORS enabled. This will fail if CORS policy is not met.
        try {
            const response = await fetch(url, { method: 'HEAD', mode: 'cors' });
            logMessage(`CORS के साथ स्ट्रीम URL स्थिति कोड: ${response.status}`, 'info');

            if (response.ok) { // response.ok checks for 2xx status codes
                logMessage('CORS के साथ स्ट्रीम URL उपलब्ध है।', 'info');
                return { success: true, message: 'CORS check passed.' };
            } else {
                logMessage(`CORS के साथ स्ट्रीम URL त्रुटि: ${response.status} ${response.statusText}`, 'error');
                return {
                    success: false,
                    code: response.status,
                    message: `CORS के साथ स्ट्रीम सर्वर से त्रुटि प्रतिक्रिया मिली: ${response.status} ${response.statusText || 'अज्ञात त्रुटि'}`,
                    troubleshoot: [
                        'सुनिश्चित करें कि स्ट्रीम सर्वर CORS अनुरोधों की अनुमति देता है।',
                        'स्ट्रीम URL सही है।'
                    ]
                };
            }
        } catch (error) {
            logMessage(`CORS के साथ स्ट्रीम URL की जांच करते समय त्रुटि: ${error.message}`, 'error');
            // This 'error' object is likely a TypeError if CORS prevents the request
            return {
                success: false,
                code: 'CORS_ERROR',
                message: `CORS (क्रॉस-ओरिजिन) नीति के कारण स्ट्रीम URL तक पहुंचने में त्रुटि: ${error.message}. यह URL सीधे एक्सेस करने योग्य नहीं हो सकता है।`,
                troubleshoot: [
                    'स्ट्रीम सर्वर पर CORS हेडर की जांच करें।',
                    'यह URL किसी भिन्न डोमेन पर हो सकता है और CORS नीति द्वारा अवरुद्ध किया जा रहा है।'
                ]
            };
        }
    } else {
        // Primary method: Try to load via a media element (which handles no-cors scenarios for media)
        return new Promise((resolve) => {
            const video = document.createElement('video');
            video.preload = 'metadata'; // Only load metadata, not the whole stream
            video.muted = true; // Mute to avoid sound if it briefly plays
            video.style.display = 'none'; // Hide the element
            document.body.appendChild(video); // Append to DOM for events to fire

            let timeoutId;

            const handleCanPlay = () => {
                clearTimeout(timeoutId);
                logMessage('मीडिया तत्व के माध्यम से स्ट्रीम URL उपलब्ध है।', 'info');
                cleanup();
                resolve({ success: true, message: 'Stream can be played by media element.' });
            };

            const handleError = (e) => {
                clearTimeout(timeoutId);
                const error = video.error;
                let errorMessage = 'अज्ञात मीडिया त्रुटि';
                let errorCode = 'UNKNOWN_MEDIA_ERROR';

                if (error) {
                    switch (error.code) {
                        case error.MEDIA_ERR_ABORTED:
                            errorMessage = 'उपयोगकर्ता ने वीडियो फेच को निरस्त कर दिया है।';
                            errorCode = 'MEDIA_ERR_ABORTED';
                            break;
                        case error.MEDIA_ERR_NETWORK:
                            errorMessage = 'नेटवर्क त्रुटि के कारण वीडियो डाउनलोड करने में विफल रहा।';
                            errorCode = 'MEDIA_ERR_NETWORK';
                            break;
                        case error.MEDIA_ERR_DECODE:
                            errorMessage = 'वीडियो डिकोडिंग त्रुटि।';
                            errorCode = 'MEDIA_ERR_DECODE';
                            break;
                        case error.MEDIA_ERR_SRC_NOT_SUPPORTED:
                            errorMessage = 'स्ट्रीम स्रोत समर्थित नहीं है या पाया नहीं जा सकता है।';
                            errorCode = 'MEDIA_ERR_SRC_NOT_SUPPORTED';
                            break;
                        default:
                            errorMessage = `मीडिया त्रुटि कोड: ${error.code}. ${error.message || ''}`;
                            errorCode = `MEDIA_ERR_${error.code}`;
                            break;
                    }
                }

                logMessage(`मीडिया तत्व के माध्यम से स्ट्रीम URL त्रुटि: ${errorMessage}`, 'error');
                cleanup();
                resolve({
                    success: false,
                    code: errorCode,
                    message: `मीडिया तत्व के माध्यम से स्ट्रीम लोड करने में त्रुटि: ${errorMessage}`,
                    troubleshoot: [
                        'सुनिश्चित करें कि स्ट्रीम URL सही है और एक वैध मीडिया फ़ाइल है।',
                        'आपके ब्राउज़र में इस मीडिया फ़ाइल का समर्थन नहीं हो सकता है।',
                        'यह एक नेटवर्क समस्या हो सकती है।',
                        'यदि स्ट्रीम HTTP पर है और आपका पेज HTTPS पर है, तो यह एक मिश्रित सामग्री त्रुटि हो सकती है।'
                    ]
                });
            };

            const cleanup = () => {
                video.removeEventListener('canplay', handleCanPlay);
                video.removeEventListener('error', handleError);
                if (video.parentNode) {
                    video.parentNode.removeChild(video);
                }
            };

            video.addEventListener('canplay', handleCanPlay);
            video.addEventListener('error', handleError);

            video.src = url;
            video.load(); // Start loading the stream metadata

            // Set a timeout in case canplay or error events don't fire
            timeoutId = setTimeout(() => {
                logMessage('स्ट्रीम उपलब्धता जांच मीडिया तत्व से समय समाप्त।', 'error');
                cleanup();
                resolve({
                    success: false,
                    code: 'TIMEOUT',
                    message: 'स्ट्रीम लोड होने में बहुत लंबा समय लगा या कोई इवेंट फायर नहीं हुआ।',
                    troubleshoot: [
                        'नेटवर्क धीमा हो सकता है।',
                        'स्ट्रीम सर्वर प्रतिक्रिया नहीं दे रहा है।'
                    ]
                });
            }, 10000); // 10 seconds timeout
        });
    }
}