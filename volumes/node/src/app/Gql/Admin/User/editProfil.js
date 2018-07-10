/*
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

import gql from "graphql-tag";
import {ErrorFragment} from '../../Fragment/error';
import {UserBaseFragment} from "../../Fragment/user";

export default gql`
  mutation adminUserEditProfil($id: ID!, $enable: Boolean, $username: String, $email: String, $password: String, $passwordConfirm: String, $registratedAt: datetime) {
    adminUserEditProfil(input: {id: $id, enable: $enable, username: $username, email: $email, password: $password, passwordConfirm: $passwordConfirm, registratedAt: $registratedAt}) {
      user {
        ...UserBaseFragment
      }
      errors {
        ...ErrorFragment
      }
    }
  }
  ${UserBaseFragment}
  ${ErrorFragment}
`;
