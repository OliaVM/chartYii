<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $surname;
    public $gender;
    public $birthday;
    public $phone;
    public $day;
    public $month;
    public $year;
    public $agree;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'string', 'min' => 6],
            ['surname', 'required'], 
            ['surname', 'string', 'max' => 70],
            ['phone', 'required'], 
            ['phone', 'string', 'max' => 20],
            ['gender', 'required'], 
            ['gender', 'integer', 'max' => 1],
            
            ['day', 'required'],
            ['month', 'required'], 
            ['year', 'required'],
            ['agree', 'required'],
            ['agree', 'validateAgreeField'],
        
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Имя',
            'surname' => 'Фамилия',
            'phone' => 'Номер телефона',
            'gender' => 'Пол',
            'birthday' => 'Дата рождения',
            'email' => 'E-mail',
            'day' => 'День рождения',

        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
       
        $user->phone = $this->phone;
        $user->surname = $this->surname;
        $user->gender = $this->gender;
        
        $user->birthday = $this->year .'-' .  $this->month . '-' . $this->day;
        $user->email = $this->email;
        
        $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
        $numChars = strlen($chars);
        $string = '';
        for ($i = 0; $i < 10; $i++) {
            $string .= substr($chars, rand(1, $numChars) - 1, 1);
        }
        $this->password =  $string;
        $this->sendPasswToUser($user);

        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        return $user->save() && $this->sendEmail($user);

    }


    public function sendPasswToUser($user) {

    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }

    public function validateAgreeField() {
        if (!$this->agree) {
            $this->addError('agree', 'Ознакомьтесь с Правилами использования сервиса');
        }
    }
}
